<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\ImageFactory;
use Atoolo\GraphQL\Search\Types\CopyrightDetails;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Rewrite\Service\UrlRewriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ImageFactory::class)]
class ImageFactoryTest extends TestCase
{
    private ImageFactory $factory;

    private UrlRewriter&MockObject $urlRewriter;

    private LoggerInterface&MockObject $logger;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->urlRewriter = $this->createMock(UrlRewriter::class);
        $this->logger = $this->createMock(LoggerInterface::class);
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

        $expectedImage = new Image(
            copyright: 'SomeCopyright',
            copyrightDetails: new CopyrightDetails(
                original: new Link(
                    url: 'https://www.images.test',
                    label: 'Original Source',
                    accessibilityLabel: null,
                    description: null,
                    opensNewWindow: true,
                    isExternal: true,
                ),
                author: new Link(
                    url: 'https://www.neverland.test/peter-pan',
                    label: 'Peter Pan',
                    accessibilityLabel: null,
                    description: null,
                    opensNewWindow: true,
                    isExternal: true,
                ),
                license: new Link(
                    url: 'https://creativecommons.org/publicdomain/zero/1.0/',
                    label: 'CC0 1.0',
                    accessibilityLabel: null,
                    description: null,
                    opensNewWindow: true,
                    isExternal: true,
                ),
            ),
            caption: 'SomeLegend',
            description: 'SomeDescription',
            alternativeText: 'SomeText',
            original: new ImageSource(
                variant: 'original',
                url: '/some_image_url.original.png',
                width: 4000,
                height: 3000,
                mediaQuery: null,
            ),
            characteristic: ImageCharacteristic::NORMAL,
            sources: [
                new ImageSource(
                    variant: 'teaser',
                    url: '/some_image_url.first.png',
                    width: 400,
                    height: 300,
                    mediaQuery: '(min-width: 1920px)',
                ),
                new ImageSource(
                    variant: 'teaser',
                    url: '/some_image_url.second.png',
                    width: 200,
                    height: 150,
                    mediaQuery: null,
                ),
            ],
        );
        $this->assertEquals(
            $expectedImage,
            $image,
            'unexpected image',
        );
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

    public function testMinimal(): void
    {
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->willReturnArgument(1);
        $resource = $this->createResource(['base' => [
            'teaser' => [
                'image' => [
                    'original' => [
                        'url' => '/some_image_url.original.png',
                        'width' => 4000,
                        'height' => 3000,
                    ],
                ],
            ],
        ]]);
        $expectedImage = new Image(
            copyright: null,
            copyrightDetails: null,
            caption: null,
            description: null,
            alternativeText: null,
            original: new ImageSource(
                variant: 'original',
                url: '/some_image_url.original.png',
                width: 4000,
                height: 3000,
                mediaQuery: null,
            ),
            characteristic: ImageCharacteristic::NORMAL,
            sources: [
            ],
        );
        $image = $this->factory->create($resource, 'teaser');
        $this->assertEquals(
            $expectedImage,
            $image,
            'unexpected image',
        );
    }

    public function testIncompleteCopyrightDetails(): void
    {
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->willReturnArgument(1);
        $resource = $this->createResource(['base' => [
            'teaser' => [
                'image' => [
                    "copyrightDetails" => [
                        "original" => [
                            "label" => "Original Source",
                            "url" => "https://www.images.test",
                        ],
                        "author" => [
                            "label" => "Peter Pan",
                        ],
                    ],
                ],
            ],
        ]]);
        $expectedImage = new Image(
            copyright: null,
            copyrightDetails: new CopyrightDetails(
                original: new Link(
                    url: 'https://www.images.test',
                    label: 'Original Source',
                    accessibilityLabel: null,
                    description: null,
                    opensNewWindow: true,
                    isExternal: true,
                ),
                author: null,
                license: null,
            ),
            caption: null,
            description: null,
            alternativeText: null,
            original: null,
            characteristic: ImageCharacteristic::NORMAL,
            sources: [
            ],
        );
        $image = $this->factory->create($resource, 'teaser');
        $this->assertEquals(
            $expectedImage,
            $image,
            'unexpected image',
        );
    }

    private function createResourceWithImage(string $characteristic = 'normal'): Resource
    {
        return $this->createResource([
            'base' => [
                'teaser' => [
                    'image' => [
                        'characteristic' => $characteristic,
                        'copyright' => 'SomeCopyright',
                        "copyrightDetails" => [
                            "original" => [
                                "label" => "Original Source",
                                "url" => "https://www.images.test",
                            ],
                            "author" => [
                                "label" => "Peter Pan",
                                "url" => "https://www.neverland.test/peter-pan",
                            ],
                            "license" => [
                                "label" => "CC0 1.0",
                                "url" => "https://creativecommons.org/publicdomain/zero/1.0/",
                            ],
                        ],
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
