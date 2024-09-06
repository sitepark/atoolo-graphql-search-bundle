<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\AssetFactory;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(ResourceAssetResolver::class)]
class ResourceAssetResolverTest extends TestCase
{
    private ResourceAssetResolver $resolver;

    private AssetFactory&MockObject $firstAssetFactory;

    private AssetFactory&MockObject $lastAssetFactory;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->firstAssetFactory = $this->createMock(
            AssetFactory::class,
        );
        $this->lastAssetFactory = $this->createMock(
            AssetFactory::class,
        );
        $this->resolver = new ResourceAssetResolver(
            [
                $this->firstAssetFactory,
                $this->lastAssetFactory,
            ],
            new NullLogger(),
        );
    }

    public function testGetAsset(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $asset = $this->createStub(Asset::class);
        $this->firstAssetFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource, null)
            ->willReturn(null);
        $this->lastAssetFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource, null)
            ->willReturn($asset);
        $result = $this->resolver->getAsset($resource, $args);
        $this->assertEquals(
            $asset,
            $result,
            'resolver should return the asset created by the first succsesfull factory',
        );
    }

    public function testGetAssetWithoutResult(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $this->firstAssetFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource, null)
            ->willReturn(null);
        $this->lastAssetFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource, null)
            ->willReturn(null);
        $result = $this->resolver->getAsset($resource, $args);
        $this->assertNull(
            $result,
            'resolver should return null as no factory provided an asset',
        );
    }

    public function testGetAssetWithWrongVariant(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->atLeastOnce())
            ->method('offsetGet')
            ->with('variant')
            ->willReturn(123);
        $args->expects($this->atLeastOnce())
            ->method('offsetExists')
            ->with('variant')
            ->willReturn(true);
        $this->expectException(\InvalidArgumentException::class);
        $this->resolver->getAsset($resource, $args);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
