<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Teaser;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceTeaserFeatureResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\TeaserFeature;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class MediaTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolver $assetResolver,
        private readonly ResourceSymbolicAssetResolver $symbolicAssetResolver,
        private readonly ResourceKickerResolver $kickerResolver,
        private readonly ResourceTeaserFeatureResolver $teaserFeatureResolver,
    ) {}

    public function getUrl(
        MediaTeaser $teaser,
    ): ?string {
        return $teaser->link?->url;
    }

    public function getKicker(
        MediaTeaser $teaser,
    ): ?string {
        return $this->kickerResolver->getKicker($teaser->resource);
    }

    public function getAsset(
        MediaTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->assetResolver->getAsset($teaser->resource, $args);
    }

    public function getSymbolicAsset(
        MediaTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->symbolicAssetResolver
            ->getSymbolicAsset($teaser->resource, $args);
    }

    /**
     * @return TeaserFeature[]
     */
    public function getFeatures(
        MediaTeaser $teaser,
        ArgumentInterface $args,
    ): array {
        return $this->teaserFeatureResolver
            ->getTeaserFeatures($teaser->resource);
    }
}
