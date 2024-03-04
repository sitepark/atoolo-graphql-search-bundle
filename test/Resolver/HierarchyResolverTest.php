<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\HierarchyResolver;
use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HierarchyResolver::class)]
class HierarchyResolverTest extends TestCase
{
    private HierarchyResolver $resolver;

    private ResourceHierarchyLoader $navigationLoader;

    private ResourceHierarchyLoader $categoryHierarchyLoader;

    public function setUp(): void
    {
        $this->navigationLoader = $this->createMock(
            ResourceHierarchyLoader::class
        );
        $this->categoryHierarchyLoader = $this->createMock(
            ResourceHierarchyLoader::class
        );
        $this->resolver = new HierarchyResolver(
            $this->navigationLoader,
            $this->categoryHierarchyLoader
        );
    }

    public function testGetCategoryRoot(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getLocation')
            ->willReturn('location');
        $hierarchy = new Hierarchy('category', $resource);
        $this->categoryHierarchyLoader->method('loadRoot')
            ->willReturn($resource);
        $this->assertEquals(
            $resource,
            $this->resolver->getRoot($hierarchy)
        );
    }

    public function testGetNavigationRoot(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getLocation')
            ->willReturn('location');
        $hierarchy = new Hierarchy('navigation', $resource);
        $this->navigationLoader->method('loadRoot')
            ->willReturn($resource);
        $this->assertEquals(
            $resource,
            $this->resolver->getRoot($hierarchy)
        );
    }

    public function testGetRootWithInvalidHierarchyType(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getLocation')
            ->willReturn('location');
        $hierarchy = new Hierarchy('test', $resource);
        $this->navigationLoader->method('loadRoot')
            ->willReturn($resource);

        $this->expectException(\InvalidArgumentException::class);
        $this->resolver->getRoot($hierarchy);
    }

    public function testGetParent(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getLocation')
            ->willReturn('location');
        $hierarchy = new Hierarchy('category', $resource);
        $this->categoryHierarchyLoader->method('loadParent')
            ->willReturn($resource);
        $this->assertEquals(
            $resource,
            $this->resolver->getParent($hierarchy)
        );
    }

    public function testGetPath(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getLocation')
            ->willReturn('location');
        $hierarchy = new Hierarchy('category', $resource);
        $this->categoryHierarchyLoader->method('loadPath')
            ->willReturn([$resource]);
        $this->assertEquals(
            [$resource],
            $this->resolver->getPath($hierarchy)
        );
    }

    public function testGetChildren(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getLocation')
            ->willReturn('location');
        $hierarchy = new Hierarchy('category', $resource);
        $this->categoryHierarchyLoader->method('loadChildren')
            ->willReturn([$resource]);
        $this->assertEquals(
            [$resource],
            $this->resolver->getChildren($hierarchy)
        );
    }
}
