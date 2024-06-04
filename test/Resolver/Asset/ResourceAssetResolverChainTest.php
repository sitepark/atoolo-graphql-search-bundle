<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolverChain;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceAssetResolverChain::class)]
class ResourceAssetResolverChainTest extends TestCase
{
    private ResourceAssetResolverChain $resolver;

    private ResourceAssetResolver&MockObject $firstInnerResolver;

    private ResourceAssetResolver&MockObject $lastInnerResolver;

    public function setUp(): void
    {
        $this->firstInnerResolver = $this->createMock(
            ResourceAssetResolver::class
        );
        $this->lastInnerResolver = $this->createMock(
            ResourceAssetResolver::class
        );
        $this->resolver = new ResourceAssetResolverChain([
            $this->firstInnerResolver,
            $this->lastInnerResolver
        ]);
    }

    public function testGetAssetWithoutResult(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $this->firstInnerResolver
            ->expects($this->once())
            ->method('getAsset');
        $this->lastInnerResolver
            ->expects($this->once())
            ->method('getAsset');
        $asset = $this->resolver->getAsset($resource, $args);
        $this->assertNull(
            $asset,
            'asset should be null'
        );
    }

    public function testGetAssetWithResultInFirst(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $expected = new Image(null, null, null, null, null, null, []);
        $this->firstInnerResolver
            ->expects($this->once())
            ->method('getAsset')
            ->willReturn($expected);
        $this->lastInnerResolver
            ->expects($this->never())
            ->method('getAsset');
        $asset = $this->resolver->getAsset($resource, $args);
        $this->assertEquals(
            $expected,
            $asset
        );
    }

    public function testGetAssetWithResultInLast(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $expected = new Image(null, null, null, null, null, null, []);
        $this->firstInnerResolver
            ->expects($this->once())
            ->method('getAsset');
        $this->lastInnerResolver
            ->expects($this->once())
            ->method('getAsset')
            ->willReturn($expected);
        $asset = $this->resolver->getAsset($resource, $args);
        $this->assertEquals(
            $expected,
            $asset
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
