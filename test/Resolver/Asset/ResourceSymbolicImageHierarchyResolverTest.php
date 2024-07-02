<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceSymbolicImageHierarchyResolver;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceSymbolicImageHierarchyResolver::class)]
class ResourceSymbolicImageHierarchyResolverTest extends TestCase
{
    private ResourceSymbolicImageHierarchyResolver $resolver;

    private UrlRewriter&MockObject $urlRewriter;

    private ResourceHierarchyLoader&MockObject $hierarchyLoader;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->urlRewriter = $this->createMock(
            UrlRewriter::class,
        );
        $this->hierarchyLoader = $this->createMock(
            ResourceHierarchyLoader::class,
        );
        $this->resolver = new ResourceSymbolicImageHierarchyResolver(
            $this->urlRewriter,
            $this->hierarchyLoader,
        );
    }

    public function testGetAssetWithoutResult(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $symbolicImage = $this->resolver->getAsset($resource, $args);
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
                    'url' => $symbolicImageUrl,
                ],
            ],
        ]);
        $parentResourceLocation = $parentResource->toLocation();
        $childResource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
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
        /** @var SymbolicImage $symbolicImage */
        $symbolicImage = $this->resolver->getAsset($childResource, $args);
        $this->assertEquals(
            $symbolicImage->url,
            $symbolicImageUrl,
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
