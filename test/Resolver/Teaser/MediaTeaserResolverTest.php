<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicImageResolver;
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

    private ResourceSymbolicImageResolver&MockObject $symbolicImageResolver;

    private ResourceKickerResolver&MockObject $kickerResolver;

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
        $this->mediaTeaserResolver = new MediaTeaserResolver(
            $this->assetResolver,
            $this->symbolicImageResolver,
            $this->kickerResolver,
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

    public function testGetSymbolicImage(): void
    {
        $this->symbolicImageResolver->expects($this->once())
            ->method('getSymbolicImage');
        $teaser = new MediaTeaser(
            null,
            null,
            null,
            null,
            null,
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->mediaTeaserResolver->getSymbolicImage($teaser, $args);
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
}
