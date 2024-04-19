<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\NewsTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NewsTeaserFactory::class)]
class NewsTeaserFactoryTest extends TestCase
{
    private NewsTeaserFactory $factory;

    private UrlRewriter $urlRewriter;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->factory = new NewsTeaserFactory(
            $this->urlRewriter,
        );
    }

    public function testUrl(): void
    {
        $resource = new Resource(
            'originalUrl',
            '',
            '',
            '',
            ResourceLanguage::default(),
            new DataBag([])
        );

        $this->urlRewriter->method('rewrite')
            ->willReturn('rewrittenUrl');

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'rewrittenUrl',
            $teaser->url,
            'unexpected url'
        );
    }

    public function testHeadline(): void
    {

        $resource = $this->createResource(
            [
                'base' => [
                    'teaser' => [
                        'headline' => 'Headline'
                    ]
                ]
            ]
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Headline',
            $teaser->headline,
            'unexpected headline'
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
            new DataBag([])
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'ResourceName',
            $teaser->headline,
            'unexpected headline'
        );
    }

    public function testText(): void
    {
        $resource = $this->createResource(
            [
                'base' => [
                    'teaser' => [
                        'text' => 'Text'
                    ]
                ]
            ]
        );

        $teaser = $this->factory->create($resource);

        $this->assertEquals(
            'Text',
            $teaser->text,
            'unexpected text'
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
            new DataBag($data)
        );
    }
}
