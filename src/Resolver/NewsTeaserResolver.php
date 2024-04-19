<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\NewsTeaser;
use DateTime;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class NewsTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ArticleTeaserResolver $articleTeaserResolver
    ) {
    }

    public function getDate(
        NewsTeaser $teaser
    ): ?DateTime {
        return $this->articleTeaserResolver->getDateFromResource(
            $teaser->resource
        );
    }

    public function getAsset(
        NewsTeaser $teaser,
        ArgumentInterface $args
    ): ?Asset {
        return $this->articleTeaserResolver->getAssetFromResource(
            $teaser->resource,
            $args
        );
    }
}
