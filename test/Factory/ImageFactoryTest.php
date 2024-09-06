<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\ImageFactory;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[CoversClass(ImageFactory::class)]
class ImageFactoryTest extends TestCase
{
    private ImageFactory $factory;

    private UrlRewriter&MockObject $urlRewriter;

    private LoggerInterface&MockObject $logger;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->logger = $this->createStub(LoggerInterface::class);
        $this->factory = new ImageFactory(
            $this->urlRewriter,
            $this->logger,
        );
    }

    public function testCreate(): void
    {
        $resource = $this->createResourceWithImage();
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->willReturnArgument(1);
        $image = $this->factory->create($resource, 'teaser');

        $this->assertEquals($image->copyright, 'SomeCopyright');
        $this->assertEquals($image->alternativeText, 'SomeText');
        $this->assertEquals($image->caption, 'SomeLegend');
        $this->assertEquals($image->description, 'SomeDescription');
        $this->assertEquals($image->characteristic, ImageCharacteristic::NORMAL);

        $this->assertEquals($image->original?->variant, 'original');
        $this->assertEquals($image->original?->url, '/some_image_url.original.png');
        $this->assertEquals($image->original?->width, 4000);
        $this->assertEquals($image->original?->height, 3000);
        $this->assertNull($image->original?->mediaQuery);

        $this->assertEquals($image->sources[0]?->variant, 'teaser');
        $this->assertEquals($image->sources[0]?->url, '/some_image_url.first.png');
        $this->assertEquals($image->sources[0]?->width, 400);
        $this->assertEquals($image->sources[0]?->height, 300);
        $this->assertEquals($image->sources[0]?->mediaQuery, '(min-width: 1920px)');

        $this->assertEquals($image->sources[1]?->variant, 'teaser');
        $this->assertEquals($image->sources[1]?->url, '/some_image_url.second.png');
        $this->assertEquals($image->sources[1]?->width, 200);
        $this->assertEquals($image->sources[1]?->height, 150);
        $this->assertNull($image->sources[1]?->mediaQuery);
    }

    public function testCreateInvalidCharacteristic(): void
    {
        $resource = $this->createResourceWithImage('invalid_characteristic_1234');
        $this->logger
            ->expects($this->atLeastOnce())
            ->method('error');
        $result = $this->factory->create($resource, 'teaser');
        $this->assertNull($result);
    }

    public function testCreateInvalidVariant(): void
    {
        $resource = $this->createResourceWithImage();
        $this->logger
            ->expects($this->atLeastOnce())
            ->method('error');
        $result = $this->factory->create($resource, null);
        $this->assertNull($result);
    }

    public function testCreateEmpty(): void
    {
        $resource = $this->createResource([]);
        $result = $this->factory->create($resource, 'teaser');
        $this->assertNull($result);
    }

    private function createResourceWithImage(string $characteristic = 'normal'): Resource
    {
        return $this->createResource([
            'base' => [
                'teaser' => [
                    'image' => [
                        'characteristic' => $characteristic,
                        'copyright' => 'SomeCopyright',
                        'text' => 'SomeText',
                        'legend' => 'SomeLegend',
                        'description' => 'SomeDescription',
                        'original' => [
                            'url' => '/some_image_url.original.png',
                            'width' => 4000,
                            'height' => 3000,
                        ],
                        'variants' => [
                            'teaser' => [
                                [
                                    'url' => '/some_image_url.first.png',
                                    'width' => 400,
                                    'height' => 300,
                                    'mediaQuery' => '(min-width: 1920px)',
                                ],
                                [
                                    'url' => '/some_image_url.second.png',
                                    'width' => 200,
                                    'height' => 150,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
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
