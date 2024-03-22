<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\MediaTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MediaTeaserFactory::class)]
class MediaTeaserFactoryTest extends TestCase
{
    private MediaTeaserFactory $factory;

    private UrlRewriter $urlRewriter;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->factory = new MediaTeaserFactory($this->urlRewriter);
    }

    public function testCreateWithUnsupportedObjectType(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getObjectType')
            ->willReturn('other');

        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create($resource);
    }

    public function testResolveWithMediaUrl(): void
    {
        $resource = $this->createResource(
            'media',
            [
                'init' => [
                    'url' => 'url',
                    'mediaUrl' => 'mediaUrl',
                ],
            ]
        );

        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(static fn ($type, $url) => $url);

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'mediaUrl',
            $teaser->url,
            'unexpected url'
        );
    }

    public function testResolveWithHeadline(): void
    {
        $resource = $this->createResource(
            'embedded-media',
            [
                'base' => [
                    'teaser' => [
                        'headline' => 'Headline'
                    ]
                ],
            ]
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Headline',
            $teaser->headline,
            'unexpected headline'
        );
    }

    public function testResolveWithFallbackHeadline(): void
    {
        $resource =  new Resource(
            '',
            '',
            'ResourceName',
            'media',
            []
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'ResourceName',
            $teaser->headline,
            'unexpected fallback headline'
        );
    }

    public function testResolveMediaTeaser(): void
    {

        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(static fn ($type, $url) => $url);

        $resource = $this->createResource(
            'media',
            [
                'init' => [
                    'mediaUrl' => 'mediaUrl'
                ],
                'base' => [
                    'teaser' => [
                        'headline' => 'Headline',
                        'text' => 'Text',
                    ],
                    'mime' => 'mime',
                    'filesize' => 100
                ],
            ]
        );

        $teaser = $this->factory->create($resource);

        $expected = new MediaTeaser(
            'mediaUrl',
            'Headline',
            'Text',
            'mime',
            100,
            null,
            $resource
        );

        $this->assertEquals(
            $expected,
            $teaser,
            'unexpected media teaser'
        );
    }

    private function createResource(string $objectType, array $array)
    {
        return new Resource(
            '',
            '',
            '',
            $objectType,
            $array
        );
    }
}
