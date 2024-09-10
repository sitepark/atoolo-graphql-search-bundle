<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceDateTimeResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicImageResolver;
use Atoolo\GraphQL\Search\Resolver\Teaser\ArticleTeaserResolver;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ArticleTeaserResolver::class)]
class ArticleTeaserResolverTest extends TestCase
{
    private ArticleTeaserResolver $resolver;

    private ResourceAssetResolver&MockObject $assetResolver;

    private ResourceSymbolicImageResolver&MockObject $symbolicImageResolver;

    private ResourceKickerResolver&MockObject $kickerResolver;

    private ResourceDateTimeResolver&MockObject $dateTimeResolver;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->assetResolver = $this->createMock(
            ResourceAssetResolver::class,
        );
        $this->symbolicImageResolver = $this->createMock(
            ResourceSymbolicImageResolver::class,
        );
        $this->kickerResolver = $this->createMock(
            ResourceKickerResolver::class,
        );
        $this->dateTimeResolver = $this->createMock(
            ResourceDateTimeResolver::class,
        );
        $this->resolver = new ArticleTeaserResolver(
            $this->assetResolver,
            $this->symbolicImageResolver,
            $this->kickerResolver,
            $this->dateTimeResolver,
        );
    }

    public function testGetUrl(): void
    {
        $url = '/some_url.php';
        $link = new Link($url);
        $teaser = new ArticleTeaser(
            $link,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $this->assertEquals(
            $url,
            $this->resolver->getUrl($teaser),
            'getUrl should return the url of the teaser link',
        );
    }

    public function testGetDate(): void
    {
        $this->dateTimeResolver->expects($this->once())
            ->method('getDate');
        $teaser = new ArticleTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );

        $this->resolver->getDate($teaser);
    }

    public function testGetAsset(): void
    {
        $this->assetResolver->expects($this->once())
            ->method('getAsset');
        $teaser = new ArticleTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getAsset($teaser, $args);
    }

    public function testGetSymbolicImage(): void
    {
        $this->symbolicImageResolver->expects($this->once())
            ->method('getSymbolicImage');
        $teaser = new ArticleTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getSymbolicImage($teaser, $args);
    }

    public function testGetKicker(): void
    {
        $this->kickerResolver->expects($this->once())
            ->method('getKicker');
        $teaser = new ArticleTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getKicker($teaser, $args);
    }
}
