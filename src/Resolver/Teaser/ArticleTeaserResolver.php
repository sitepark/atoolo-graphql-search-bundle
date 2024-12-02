<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceActionLinkResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceDateTimeResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\Link;
use DateTime;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ArticleTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolver $assetResolver,
        private readonly ResourceSymbolicAssetResolver $symbolicAssetResolver,
        private readonly ResourceKickerResolver $kickerResolver,
        private readonly ResourceDateTimeResolver $dateResolver,
        private readonly ResourceActionLinkResolver $actionLinkResolver,
    ) {}

    public function getUrl(
        ArticleTeaser $teaser,
    ): ?string {
        return $teaser->link?->url;
    }

    public function getKicker(
        ArticleTeaser $teaser,
    ): ?string {
        return $this->kickerResolver->getKicker($teaser->resource);
    }

    public function getDate(
        ArticleTeaser $teaser,
    ): ?DateTime {
        return $this->dateResolver->getDate($teaser->resource);
    }

    public function getAsset(
        ArticleTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->assetResolver->getAsset($teaser->resource, $args);
    }

    public function getSymbolicAsset(
        ArticleTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->symbolicAssetResolver
            ->getSymbolicAsset($teaser->resource, $args);
    }

    /**
     * @return Link[]
     */
    public function getActions(
        ArticleTeaser $teaser,
        ArgumentInterface $args,
    ): array {
        return $this->actionLinkResolver
            ->getActionLinks($teaser->resource);
    }
}
