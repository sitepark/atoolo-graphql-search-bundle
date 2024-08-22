<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Asset\ResourceSymbolicImageResolver;
use Atoolo\GraphQL\Search\Resolver\NewsTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceDateResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceLinkNewWindowResolver;
use Atoolo\GraphQL\Search\Types\NewsTeaser;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(NewsTeaserResolver::class)]
class NewsTeaserResolverTest extends TestCase
{
    private NewsTeaserResolver $newsTeaserResolver;

    private ResourceAssetResolver&MockObject $assetResolver;

    private ResourceSymbolicImageResolver&MockObject $symbolicImageResolver;

    private ResourceKickerResolver&MockObject $kickerResolver;

    private ResourceDateResolver&MockObject $dateResolver;

    private ResourceLinkNewWindowResolver&MockObject $linkNewWindowResolver;

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
        $this->dateResolver = $this->createMock(
            ResourceDateResolver::class,
        );
        $this->linkNewWindowResolver = $this->createMock(
            ResourceLinkNewWindowResolver::class,
        );
        $this->newsTeaserResolver = new NewsTeaserResolver(
            $this->assetResolver,
            $this->symbolicImageResolver,
            $this->kickerResolver,
            $this->dateResolver,
            $this->linkNewWindowResolver,
        );
    }

    public function testGetDate(): void
    {
        $this->dateResolver->expects($this->once())
            ->method('getDate');
        $teaser = new NewsTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class),
        );

        $this->newsTeaserResolver->getDate($teaser);
    }

    public function testGetAsset(): void
    {
        $this->assetResolver->expects($this->once())
            ->method('getAsset');
        $teaser = new NewsTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->newsTeaserResolver->getAsset($teaser, $args);
    }

    public function testGetSymbolicImage(): void
    {
        $this->symbolicImageResolver->expects($this->once())
            ->method('getSymbolicImage');
        $teaser = new NewsTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->newsTeaserResolver->getSymbolicImage($teaser, $args);
    }

    public function testGetKicker(): void
    {
        $this->kickerResolver->expects($this->once())
            ->method('getKicker');
        $teaser = new NewsTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class),
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->newsTeaserResolver->getKicker($teaser, $args);
    }

    public function testGetLinkNewWindow(): void
    {
        $this->linkNewWindowResolver->expects($this->once())
            ->method('getLinkNewWindow');
        $teaser = new NewsTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class),
        );
        $this->newsTeaserResolver->getLinkNewWindow($teaser);
    }
}
