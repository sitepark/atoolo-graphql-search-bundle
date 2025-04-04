<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceTeaserFeatureResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceDateTimeResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
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

    private ResourceSymbolicAssetResolver&MockObject $symbolicAssetResolver;

    private ResourceKickerResolver&MockObject $kickerResolver;

    private ResourceDateTimeResolver&MockObject $dateTimeResolver;

    private ResourceTeaserFeatureResolver&MockObject $teaserFeatureResolver;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->assetResolver = $this->createMock(
            ResourceAssetResolver::class,
        );
        $this->symbolicAssetResolver = $this->createMock(
            ResourceSymbolicAssetResolver::class,
        );
        $this->kickerResolver = $this->createMock(
            ResourceKickerResolver::class,
        );
        $this->dateTimeResolver = $this->createMock(
            ResourceDateTimeResolver::class,
        );
        $this->teaserFeatureResolver = $this->createMock(
            ResourceTeaserFeatureResolver::class,
        );
        $this->resolver = new ArticleTeaserResolver(
            $this->assetResolver,
            $this->symbolicAssetResolver,
            $this->kickerResolver,
            $this->dateTimeResolver,
            $this->teaserFeatureResolver,
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

    public function testGetSymbolicAsset(): void
    {
        $this->symbolicAssetResolver->expects($this->once())
            ->method('getSymbolicAsset');
        $teaser = new ArticleTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getSymbolicAsset($teaser, $args);
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

    public function testGetFeatures(): void
    {
        $this->teaserFeatureResolver->expects($this->once())
            ->method('getTeaserFeatures');
        $teaser = new ArticleTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);
        $this->resolver->getFeatures($teaser, $args);
    }
}
