<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ArticleTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use DateTime;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ArticleTeaserResolver::class)]
class ArticleTeaserResolverTest extends TestCase
{
    private ArticleTeaserResolver $resolver;

    private UrlRewriter $urlRewriter;

    private LoggerInterface&MockObject $logger;

    public function setUp(): void
    {
        $this->urlRewriter = $this->createStub(UrlRewriter::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->resolver = new ArticleTeaserResolver(
            $this->urlRewriter,
            $this->logger
        );
    }

    public function testGetDate(): void
    {
        $date = new DateTime();
        $date->setDate(19, 4, 2024);
        $date->setTime(9, 28);

        $teaser = $this->createArticleTeaser([
            'base' => [
                'teaser' => [
                    'date' => $date->getTimestamp()
                ]
            ]
        ]);

        $teaserDate = $this->resolver->getDate($teaser);

        $this->assertEquals(
            $date,
            $teaserDate,
            'unexpected teaser date'
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

    public function testGetAssertWithImageAndInvalidCharacteristic(): void
    {
        $this->urlRewriter->method('rewrite')
            ->willReturnCallback(fn($type, $url) => $url);

        $this->logger->expects($this->once())->method('error');

        $teaser = $this->createArticleTeaserWithImage(
            [
                'characteristic' => 'invalid',
            ]
        );

        $args = new Argument(['variant' => 'teaser']);
        $this->resolver->getAsset($teaser, $args);
    }

    private function createArticleTeaserWithImage(
        array $imageData
    ): ArticleTeaser {
        return new ArticleTeaser(
            '',
            '',
            '',
            TestResourceFactory::create([
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
            TestResourceFactory::create($data)
        );
    }
}
