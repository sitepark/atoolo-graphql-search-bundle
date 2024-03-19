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
class MediaTeaserResolverTest extends TestCase
{
    private MediaTeaserFactory $resolver;

    private UrlRewriter $urlRewriter;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->resolver = new MediaTeaserFactory($this->urlRewriter);
    }

    public function testAcceptMedia(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getObjectType')
            ->willReturn('media');

        $this->assertTrue(
            $this->resolver->accept($resource),
            'Resolver should accept media resource'
        );
    }

    public function testAcceptEmbeddedMedia(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getObjectType')
            ->willReturn('embedded-media');

        $this->assertTrue(
            $this->resolver->accept($resource),
            'Resolver should accept embedded-media resource'
        );
    }

    public function testNotAcceptOtherTypes(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getObjectType')
            ->willReturn('other');

        $this->assertFalse(
            $this->resolver->accept($resource),
            'Resolver should not accept other resource'
        );
    }

    public function testResolveWithMediaUrl(): void
    {
        $resource = $this->createResource(
            [
                'init' => [
                    'url' => 'url',
                    'mediaUrl' => 'mediaUrl',
                ],
            ]
        );

        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(static fn ($type, $url) => $url);

        $teaser = $this->resolver->resolve($resource);

        $this->assertEquals(
            'mediaUrl',
            $teaser->url,
            'unexpected url'
        );
    }

    public function testResolveWithHeadline(): void
    {
        $resource = $this->createResource(
            [
                'base' => [
                    'teaser' => [
                        'headline' => 'Headline'
                    ]
                ],
            ]
        );

        $teaser = $this->resolver->resolve($resource);

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
            '',
            []
        );

        $teaser = $this->resolver->resolve($resource);

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

        $teaser = $this->resolver->resolve($resource);

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

    private function createResource(array $array)
    {
        return new Resource(
            '',
            '',
            '',
            '',
            $array
        );
    }
}
