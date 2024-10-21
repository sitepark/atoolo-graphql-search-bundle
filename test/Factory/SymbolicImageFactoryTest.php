<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\SymbolicImageFactory;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceLanguage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(SymbolicImageFactory::class)]
class SymbolicImageFactoryTest extends TestCase
{
    private SymbolicImageFactory $factory;

    private UrlRewriter&MockObject $urlRewriter;

    private ResourceHierarchyLoader&MockObject $hierarchyLoader;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->hierarchyLoader = $this->createStub(ResourceHierarchyLoader::class);
        $this->factory = new SymbolicImageFactory(
            $this->urlRewriter,
            $this->hierarchyLoader,
        );
    }

    public function testGetAssetWithoutResult(): void
    {
        $resource = $this->createResource([]);
        $symbolicImage = $this->factory->create($resource);
        $this->assertNull(
            $symbolicImage,
            'symbolicImage should be null',
        );
    }

    public function testGetAssetWithResult(): void
    {
        $symbolicImageUrl = '/some_url.svg';
        $parentResource = $this->createResource([
            'base' => [
                'symbolicImage' => [
                    'content' => [
                        'url' => $symbolicImageUrl,
                    ],
                ],
            ],
        ]);
        $parentResourceLocation = $parentResource->toLocation();
        $childResource = $this->createResource([]);
        $this->hierarchyLoader
            ->expects($this->atLeastOnce())
            ->method('getPrimaryParentLocation')
            ->with($childResource)
            ->willReturn($parentResourceLocation);
        $this->hierarchyLoader
            ->expects($this->atLeastOnce())
            ->method('load')
            ->with($parentResourceLocation)
            ->willReturn($parentResource);
        $this->urlRewriter
            ->expects($this->once())
            ->method('rewrite')
            ->with(
                UrlRewriterType::IMAGE,
                $symbolicImageUrl,
            )->willReturn(
                $symbolicImageUrl,
            );
        $symbolicImage = $this->factory->create($childResource);
        $this->assertEquals(
            $symbolicImage?->url,
            $symbolicImageUrl,
        );
    }

    private function createResource(array $data): Resource
    {
        return new Resource(
            $data['url'] ?? '',
            $data['id'] ?? '',
            $data['name'] ?? '',
            $data['objectType'] ?? '',
            ResourceLanguage::default(),
            new DataBag($data),
        );
    }
}
