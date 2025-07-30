<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceTeaserFeatureResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Teaser\MediaTeaserResolver;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(MediaTeaserResolver::class)]
class MediaTeaserResolverTest extends TestCase
{
    private MediaTeaserResolver $mediaTeaserResolver;

    private ResourceAssetResolver&MockObject $assetResolver;

    private ResourceSymbolicAssetResolver&MockObject $symbolicAssetResolver;

    private ResourceKickerResolver&MockObject $kickerResolver;

    private ResourceTeaserFeatureResolver&MockObject $teaserFeatureResolver;

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
        $this->teaserFeatureResolver = $this->createMock(
            ResourceTeaserFeatureResolver::class,
        );
        $this->mediaTeaserResolver = new MediaTeaserResolver(
            $this->assetResolver,
            $this->symbolicAssetResolver,
            $this->kickerResolver,
            $this->teaserFeatureResolver,
        );
    }

    public function testGetUrl(): void
    {
        $url = '/some_url.php';
        $link = new Link($url);
        $teaser = new MediaTeaser(
            $link,
            null,
            null,
            null,
            null,
            $this->createStub(Resource::class),
        );
        $this->assertEquals(
            $url,
            $this->mediaTeaserResolver->getUrl($teaser),
            'getUrl should return the url of the teaser link',
        );
    }

    public function testGetAsset(): void
    {
        $this->assetResolver->expects($this->once())
            ->method('getAsset');
        $teaser = new MediaTeaser(
            null,
            null,
            null,
            null,
            null,
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->mediaTeaserResolver->getAsset($teaser, $args);
    }

    public function testGetSymbolicAsset(): void
    {
        $this->symbolicAssetResolver->expects($this->once())
            ->method('getSymbolicAsset');
        $teaser = new MediaTeaser(
            null,
            null,
            null,
            null,
            null,
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->mediaTeaserResolver->getSymbolicAsset($teaser, $args);
    }

    public function testGetKicker(): void
    {
        $this->kickerResolver->expects($this->once())
            ->method('getKicker');
        $teaser = new MediaTeaser(
            null,
            null,
            null,
            null,
            null,
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);
        $this->mediaTeaserResolver->getKicker($teaser, $args);
    }

    public function testGetFeatures(): void
    {
        $this->teaserFeatureResolver->expects($this->once())
            ->method('getTeaserFeatures');
        $teaser = new MediaTeaser(
            null,
            null,
            null,
            null,
            null,
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);
        $this->mediaTeaserResolver->getFeatures($teaser, $args);
    }
}
