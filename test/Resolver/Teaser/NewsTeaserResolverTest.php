<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceActionLinkResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceDateTimeResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Teaser\NewsTeaserResolver;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\GraphQL\Search\Types\NewsTeaser;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(NewsTeaserResolver::class)]
class NewsTeaserResolverTest extends TestCase
{
    private NewsTeaserResolver $resolver;

    private ResourceAssetResolver&MockObject $assetResolver;

    private ResourceSymbolicAssetResolver&MockObject $symbolicAssetResolver;

    private ResourceKickerResolver&MockObject $kickerResolver;

    private ResourceDateTimeResolver&MockObject $dateTimeResolver;

    private ResourceActionLinkResolver&MockObject $actionLinkResolver;

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
        $this->actionLinkResolver = $this->createMock(
            ResourceActionLinkResolver::class,
        );
        $this->resolver = new NewsTeaserResolver(
            $this->assetResolver,
            $this->symbolicAssetResolver,
            $this->kickerResolver,
            $this->dateTimeResolver,
            $this->actionLinkResolver,
        );
    }

    public function testGetUrl(): void
    {
        $url = '/some_url.php';
        $link = new Link($url);
        $teaser = new NewsTeaser(
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
        $teaser = new NewsTeaser(
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
        $teaser = new NewsTeaser(
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
        $teaser = new NewsTeaser(
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
        $teaser = new NewsTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getKicker($teaser, $args);
    }

    public function testGetActions(): void
    {
        $this->actionLinkResolver->expects($this->once())
            ->method('getActionLinks');
        $teaser = new NewsTeaser(
            null,
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);
        $this->resolver->getActions($teaser, $args);
    }
}
