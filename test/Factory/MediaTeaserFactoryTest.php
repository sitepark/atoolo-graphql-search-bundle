<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\LinkFactory;
use Atoolo\GraphQL\Search\Factory\MediaTeaserFactory;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MediaTeaserFactory::class)]
class MediaTeaserFactoryTest extends TestCase
{
    private MediaTeaserFactory $factory;

    private LinkFactory $linkFactory;

    public function setUp(): void
    {
        $this->linkFactory = $this->createStub(LinkFactory::class);
        $this->factory = new MediaTeaserFactory($this->linkFactory);
    }

    public function testCreateWithUnsupportedObjectType(): void
    {
        $resource = TestResourceFactory::create([
            'objectType' => 'other',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->factory->create($resource);
    }

    public function testLink(): void
    {
        $resource = TestResourceFactory::create([
            'objectType' => 'media',
        ]);
        $link = new Link('url');
        $this->linkFactory->method('create')
            ->willReturn($link);

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            $link,
            $teaser->link,
            'unexpected link',
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
        $link = new Link('url');
        $this->linkFactory->method('create')
            ->willReturn($link);

        $resource = TestResourceFactory::create([
            'objectType' => 'media',
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
            $link,
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
