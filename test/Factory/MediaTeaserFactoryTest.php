<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\MediaTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
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
        $resource = TestResourceFactory::create([
            'objectType' => 'other',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create($resource);
    }

    public function testResolveWithMediaUrl(): void
    {
        $resource = TestResourceFactory::create([
            'objectType' => 'media',
            'url' => 'url',
            'mediaUrl' => 'mediaUrl',
        ]);

        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(static fn($type, $url) => $url);

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'mediaUrl',
            $teaser->url,
            'unexpected url',
        );
    }

    public function testResolveWithHeadline(): void
    {
        $resource = TestResourceFactory::create([
            'objectType' => 'embedded-media',
            'base' => [
                'teaser' => [
                    'headline' => 'Headline',
                ],
            ],
        ]);

        /** @var MediaTeaser $teaser */
        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Headline',
            $teaser->headline,
            'unexpected headline',
        );
    }

    public function testResolveWithFallbackHeadline(): void
    {
        $resource = TestResourceFactory::create([
            'objectType' => 'media',
            'name' => 'ResourceName',
        ]);

        /** @var MediaTeaser $teaser */
        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'ResourceName',
            $teaser->headline,
            'unexpected fallback headline',
        );
    }

    public function testResolveMediaTeaser(): void
    {

        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(static fn($type, $url) => $url);

        $resource = TestResourceFactory::create([
            'objectType' => 'media',
            'mediaUrl' => 'mediaUrl',
            'base' => [
                'teaser' => [
                    'headline' => 'Headline',
                    'text' => 'Text',
                ],
                'mime' => 'mime',
                'filesize' => 100,
            ],
        ]);

        $teaser = $this->factory->create($resource);

        $expected = new MediaTeaser(
            'mediaUrl',
            'Headline',
            'Text',
            'mime',
            100,
            $resource,
        );

        $this->assertEquals(
            $expected,
            $teaser,
            'unexpected media teaser',
        );
    }
}
