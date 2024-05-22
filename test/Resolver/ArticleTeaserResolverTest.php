<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ArticleTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceDateResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
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

    private ResourceKickerResolver&MockObject $kickerResolver;

    private ResourceDateResolver&MockObject $dateResolver;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->assetResolver = $this->createMock(
            ResourceAssetResolver::class
        );
        $this->kickerResolver = $this->createMock(
            ResourceKickerResolver::class
        );
        $this->dateResolver = $this->createMock(
            ResourceDateResolver::class
        );
        $this->resolver = new ArticleTeaserResolver(
            $this->assetResolver,
            $this->kickerResolver,
            $this->dateResolver
        );
    }

    public function testGetDate(): void
    {
        $this->dateResolver->expects($this->once())
            ->method('getDate');
        $teaser = new ArticleTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class)
        );

        $this->resolver->getDate($teaser);
    }

    public function testGetAsset(): void
    {
        $this->assetResolver->expects($this->once())
            ->method('getAsset');
        $teaser = new ArticleTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class)
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getAsset($teaser, $args);
    }

    public function testGetKicker(): void
    {
        $this->kickerResolver->expects($this->once())
            ->method('getKicker');
        $teaser = new ArticleTeaser(
            '',
            '',
            '',
            $this->createStub(Resource::class)
        );
        $args = $this->createStub(ArgumentInterface::class);

        $this->resolver->getKicker($teaser, $args);
    }
}
