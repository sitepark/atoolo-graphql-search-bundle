<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\LinkFactory;
use Atoolo\GraphQL\Search\Factory\NewsTeaserFactory;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Link;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NewsTeaserFactory::class)]
class NewsTeaserFactoryTest extends TestCase
{
    private NewsTeaserFactory $factory;

    private LinkFactory $linkFactory;

    public function setUp(): void
    {
        $this->linkFactory = $this->createStub(LinkFactory::class);
        $this->factory = new NewsTeaserFactory(
            $this->linkFactory,
        );
    }

    public function testLink(): void
    {
        $resource = TestResourceFactory::create([
            'url' => 'originalUrl',
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

    public function testHeadline(): void
    {

        $resource = TestResourceFactory::create([
            'base' => [
                'teaser' => [
                    'headline' => 'Headline',
                ],
            ],
        ]);

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Headline',
            $teaser->headline,
            'unexpected headline',
        );
    }

    public function testHeadlineFallback(): void
    {
        $resource = TestResourceFactory::create([
            'name' => 'ResourceName',
        ]);

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'ResourceName',
            $teaser->headline,
            'unexpected headline',
        );
    }

    public function testText(): void
    {
        $resource = TestResourceFactory::create(
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
}
