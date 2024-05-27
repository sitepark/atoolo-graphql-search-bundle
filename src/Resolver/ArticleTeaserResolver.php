<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolverChain;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Asset;
use DateTime;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

/**
 * @phpstan-type ImageData array{
 *     characteristic?: string,
 *     copyright?: string,
 *     text?: string,
 *     legend?: string,
 *     description?: string,
 *     original? : ImageSourceData,
 *     variants?: array<string,array<ImageSourceData>>
 * }
 *
 * @phpstan-type ImageSourceData array{
 *     url: string,
 *     width: int,
 *     height: int,
 *     mediaQuery?: string
 * }
 */
class ArticleTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolverChain $assetResolver,
        private readonly ResourceKickerResolver $kickerResolver,
        private readonly ResourceDateResolver $dateResolver
    ) {
    }

    public function getKicker(
        ArticleTeaser $teaser
    ): ?string {
        return $this->kickerResolver->getKicker($teaser->resource);
    }

    public function getDate(
        ArticleTeaser $teaser
    ): ?DateTime {
        return $this->dateResolver->getDate($teaser->resource);
    }

    public function getAsset(
        ArticleTeaser $teaser,
        ArgumentInterface $args
    ): ?Asset {
        return $this->assetResolver->getAsset($teaser->resource, $args);
    }
}
