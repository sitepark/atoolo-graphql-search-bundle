<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ArticleTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleTeaserResolver::class)]
class ArticleTeaserResolverTest extends TestCase
{
    private ArticleTeaserResolver $resolver;

    private UrlRewriter $urlRewriter;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->resolver = new ArticleTeaserResolver($this->urlRewriter);
    }

    public function testAccept(): void
    {
        $resource = $this->createStub(Resource::class);

        $this->assertTrue(
            $this->resolver->accept($resource),
            'Resolver should accept any resource'
        );
    }

    public function testUrl(): void
    {
        $resource = $this->createStub(Resource::class);

        $this->urlRewriter->method('rewrite')
            ->willReturn('rewrittenUrl');

        $teaser = $this->resolver->resolve($resource);

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

        $teaser = $this->resolver->resolve($resource);

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
            []
        );

        $teaser = $this->resolver->resolve($resource);

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

        $teaser = $this->resolver->resolve($resource);

        $this->assertEquals(
            'Text',
            $teaser->text,
            'unexpected text'
        );
    }

    public function testGetAssertWithoutImage(): void
    {
        $teaser = $this->createArticleTeaser([]);

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
            ->willReturnCallback(fn($type, $url) => $url);

        $teaser = $this->createArticleTeaserWithImage(
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
        $image = $this->resolver->getAsset($teaser, $args);

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

    private function createArticleTeaserWithImage(
        array $imageData
    ): ArticleTeaser {
        return new ArticleTeaser(
            '',
            '',
            '',
            null,
            $this->createResource([
                    'base' => [
                        'teaser' => [
                            'image' => $imageData
                        ]
                    ]
                ])
        );
    }

    private function createArticleTeaser(array $data): ArticleTeaser
    {
        return new ArticleTeaser(
            '',
            '',
            '',
            null,
            $this->createResource($data)
        );
    }

    private function createResource(array $data): Resource
    {
        return new Resource(
            '',
            '',
            '',
            '',
            $data
        );
    }
}
