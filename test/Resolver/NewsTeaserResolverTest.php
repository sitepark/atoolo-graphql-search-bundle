<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ArticleTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\NewsTeaserResolver;
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

    private ArticleTeaserResolver&MockObject $articleTeaserResolver;

    public function setUp(): void
    {
        $this->articleTeaserResolver = $this->createMock(
            ArticleTeaserResolver::class
        );
        $this->newsTeaserResolver = new NewsTeaserResolver(
            $this->articleTeaserResolver
        );
    }

    public function testGetDate(): void
    {
        $this->articleTeaserResolver->expects($this->once())
            ->method('getDateFromResource');
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
        $this->articleTeaserResolver->expects($this->once())
            ->method('getAssetFromResource');
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
