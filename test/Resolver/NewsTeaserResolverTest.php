<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\NewsTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceDateResolver;
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

    private ResourceDateResolver&MockObject $dateResolver;

    public function setUp(): void
    {
        $this->assetResolver = $this->createMock(
            ResourceAssetResolver::class
        );
        $this->dateResolver = $this->createMock(
            ResourceDateResolver::class
        );
        $this->newsTeaserResolver = new NewsTeaserResolver(
            $this->assetResolver,
            $this->dateResolver
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
            $this->createStub(Resource::class)
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
            $this->createStub(Resource::class)
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->newsTeaserResolver->getAsset($teaser, $args);
    }
}
