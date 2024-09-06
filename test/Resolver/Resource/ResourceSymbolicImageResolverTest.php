<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\AssetFactory;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicImageResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(ResourceSymbolicImageResolver::class)]
class ResourceSymbolicImageResolverTest extends TestCase
{
    private ResourceSymbolicImageResolver $resolver;

    private AssetFactory&MockObject $assetFactory;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->assetFactory = $this->createMock(
            AssetFactory::class,
        );
        $this->resolver = new ResourceSymbolicImageResolver(
            $this->assetFactory,
            new NullLogger(),
        );
    }

    public function testGetSymbolicImage(): void
    {
        $resource = $this->createResource([]);
        $symbolicImage = new SymbolicImage('/some_url.svg');
        $args = $this->createStub(ArgumentInterface::class);
        $this->assetFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource, null)
            ->willReturn($symbolicImage);
        $result = $this->resolver->getSymbolicImage($resource, $args);
        $this->assertEquals(
            $symbolicImage,
            $result,
            'resolver should return the symbolic image created by the factory',
        );
    }

    public function testGetSymbolicImageWithWrongFactory(): void
    {
        $resource = $this->createResource([]);
        $image = new Image(
            '',
            '',
            '',
            '',
            null,
            null,
            [],
        );
        $args = $this->createStub(ArgumentInterface::class);
        $this->assetFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource, null)
            ->willReturn($image);
        $result = $this->resolver->getSymbolicImage($resource, $args);
        $this->assertNull(
            $result,
            'resolver should return null if the factory does not return a SymbolicImage',
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
        $this->resolver->getSymbolicImage($resource, $args);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
