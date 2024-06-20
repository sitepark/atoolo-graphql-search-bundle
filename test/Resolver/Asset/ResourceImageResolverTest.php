<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceImageResolver;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ResourceImageResolver::class)]
class ResourceImageResolverTest extends TestCase
{
    private ResourceImageResolver $resolver;

    private UrlRewriter&MockObject $urlRewriter;

    private LoggerInterface&MockObject $logger;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->urlRewriter = $this->createMock(
            UrlRewriter::class
        );
        $this->logger = $this->createMock(
            LoggerInterface::class
        );

        $this->resolver = new ResourceImageResolver(
            $this->urlRewriter,
            $this->logger
        );
    }

    public function testGetAssertWithoutImage(): void
    {
        $teaser = $this->createResource([]);

        $args = $this->createStub(ArgumentInterface::class);
        $asset = $this->resolver->getAsset($teaser, $args);

        $this->assertNull(
            $asset,
            'asset should be null'
        );
    }

    public function testGetAssertWithImage(): void
    {
        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(fn ($type, $url) => $url);

        $resource = $this->createResourceWithImage(
            [
                'characteristic' => 'decorativeImage',
                'copyright' => 'my copyright',
                'text' => 'alternative text',
                'legend' => 'caption',
                'description' => 'description',
                'original' => [
                    'url' => 'originalUrl',
                    'width' => 100,
                    'height' => 100
                ],
                'variants' => [
                    'teaser' => [
                        [
                            'url' => 'normalUrl',
                            'width' => 100,
                            'height' => 100,
                            'mediaQuery' => 'mediaQuery'
                        ]
                    ]
                ]
            ]
        );

        $args = new Argument(['variant' => 'teaser']);
        $image = $this->resolver->getAsset($resource, $args);

        $expected = new Image(
            'my copyright',
            'caption',
            'description',
            'alternative text',
            new ImageSource(
                'original',
                'originalUrl',
                100,
                100
            ),
            ImageCharacteristic::DECORATIVE_IMAGE,
            [
                new ImageSource(
                    'teaser',
                    'normalUrl',
                    100,
                    100,
                    'mediaQuery'
                )
            ]
        );

        $this->assertEquals(
            $expected,
            $image,
            'unexpected image'
        );
    }

    public function testGetAssertWithImageAndInvalidCharacteristic(): void
    {
        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(fn ($type, $url) => $url);

        $this->logger->expects($this->once())->method('error');

        $resource = $this->createResourceWithImage(
            [
                'characteristic' => 'invalid',
            ]
        );

        $args = new Argument(['variant' => 'teaser']);
        $this->resolver->getAsset($resource, $args);
    }

    /**
     * @param array<string,mixed> $imageData
     */
    private function createResourceWithImage(
        array $imageData
    ): Resource {
        return TestResourceFactory::create([
            'base' => [
                'teaser' => [
                    'image' => $imageData
                ]
            ]
        ]);
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
