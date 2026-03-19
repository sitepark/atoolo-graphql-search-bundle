<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\SymbolicAssetFactory;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Rewrite\Dto\UrlRewriteType;
use Atoolo\Rewrite\Service\UrlRewriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(SymbolicAssetFactory::class)]
class SymbolicAssetFactoryTest extends TestCase
{
    private SymbolicAssetFactory $factory;

    private UrlRewriter&MockObject $urlRewriter;

    private ResourceHierarchyLoader&MockObject $hierarchyLoader;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->urlRewriter = $this->createMock(UrlRewriter::class);
        $this->hierarchyLoader = $this->createMock(ResourceHierarchyLoader::class);
        $this->factory = new SymbolicAssetFactory(
            $this->urlRewriter,
            $this->hierarchyLoader,
        );
    }

    public function testGetAssetWithoutResult(): void
    {
        $resource = TestResourceFactory::create([]);
        $symbolicAsset = $this->factory->create($resource);
        $this->assertNull(
            $symbolicAsset,
            'symbolicAsset should be null',
        );
    }

    public function testGetAssetWithResult(): void
    {
        $symbolicAssetUrl = '/some_url.svg';
        $parentResource = TestResourceFactory::create([
            'base' => [
                'symbolicAsset' => [
                    'content' => [
                        'url' => $symbolicAssetUrl,
                    ],
                ],
            ],
        ]);
        $parentResourceLocation = $parentResource->toLocation();
        $childResource = TestResourceFactory::create([]);
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
                UrlRewriteType::IMAGE,
                $symbolicAssetUrl,
            )->willReturn(
                $symbolicAssetUrl,
            );
        $symbolicAsset = $this->factory->create($childResource);
        $this->assertEquals(
            $symbolicAsset?->url,
            $symbolicAssetUrl,
        );
    }
}
