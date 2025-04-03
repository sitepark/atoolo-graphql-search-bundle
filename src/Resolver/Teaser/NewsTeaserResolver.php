<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceTeaserFeatureResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceDateTimeResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\TeaserFeature;
use Atoolo\GraphQL\Search\Types\NewsTeaser;
use DateTime;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class NewsTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolver $assetResolver,
        private readonly ResourceSymbolicAssetResolver $symbolicAssetResolver,
        private readonly ResourceKickerResolver $kickerResolver,
        private readonly ResourceDateTimeResolver $dateResolver,
        private readonly ResourceTeaserFeatureResolver $teaserFeatureResolver,
    ) {}

    public function getUrl(
        NewsTeaser $teaser,
    ): ?string {
        return $teaser->link?->url;
    }

    public function getKicker(
        NewsTeaser $teaser,
    ): ?string {
        return $this->kickerResolver->getKicker($teaser->resource);
    }

    public function getDate(
        NewsTeaser $teaser,
    ): ?DateTime {
        return $this->dateResolver->getDate($teaser->resource);
    }

    public function getAsset(
        NewsTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->assetResolver->getAsset($teaser->resource, $args);
    }

    public function getSymbolicAsset(
        NewsTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->symbolicAssetResolver
            ->getSymbolicAsset($teaser->resource, $args);
    }

    /**
     * @return TeaserFeature[]
     */
    public function getFeatures(
        NewsTeaser $teaser,
        ArgumentInterface $args,
    ): array {
        return $this->teaserFeatureResolver
            ->getTeaserFeatures($teaser->resource);
    }
}
