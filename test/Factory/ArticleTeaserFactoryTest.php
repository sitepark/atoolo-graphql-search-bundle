<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\ArticleTeaserFactory;
use Atoolo\GraphQL\Search\Factory\LinkFactory;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleTeaserFactory::class)]
class ArticleTeaserFactoryTest extends TestCase
{
    private ArticleTeaserFactory $factory;

    private LinkFactory $linkFactory;

    public function setUp(): void
    {
        $this->linkFactory = $this->createStub(LinkFactory::class);
        $this->factory = new ArticleTeaserFactory(
            $this->linkFactory,
        );
    }

    public function testLink(): void
    {
        $resource = new Resource(
            'originalUrl',
            '',
            '',
            '',
            ResourceLanguage::default(),
            new DataBag([]),
        );
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

    public function testHeadline(): void
    {

        $resource = $this->createResource(
            [
                'base' => [
                    'teaser' => [
                        'headline' => 'Headline',
                    ],
                ],
            ],
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Headline',
            $teaser->headline,
            'unexpected headline',
        );
    }

    public function testHeadlineFallback(): void
    {
        $resource = new Resource(
            '',
            '',
            'ResourceName',
            '',
            ResourceLanguage::default(),
            new DataBag([]),
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'ResourceName',
            $teaser->headline,
            'unexpected headline',
        );
    }

    public function testText(): void
    {
        $resource = $this->createResource(
            [
                'base' => [
                    'teaser' => [
                        'text' => 'Text',
                    ],
                ],
            ],
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Text',
            $teaser->text,
            'unexpected text',
        );
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
