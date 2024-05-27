<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolverChain;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\NewsTeaser;
use DateTime;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class NewsTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolverChain $assetResolver,
        private readonly ResourceDateResolver $dateResolver
    ) {
    }

    public function getDate(
        NewsTeaser $teaser
    ): ?DateTime {
        return $this->dateResolver->getDate($teaser->resource);
    }

    public function getAsset(
        NewsTeaser $teaser,
        ArgumentInterface $args
    ): ?Asset {
        return $this->assetResolver->getAsset($teaser->resource, $args);
    }
}
