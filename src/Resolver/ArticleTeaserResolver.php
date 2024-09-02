<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Asset\ResourceSymbolicImageResolver;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Asset;
use DateTime;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ArticleTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolver $assetResolver,
        private readonly ResourceSymbolicImageResolver $symbolicImageResolver,
        private readonly ResourceKickerResolver $kickerResolver,
        private readonly ResourceDateResolver $dateResolver,
        private readonly ResourceOpensNewWindowResolver $opensNewWindowResolver,
    ) {}

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

    public function getSymbolicImage(
        ArticleTeaser $teaser,
        ArgumentInterface $args,
    ): ?Asset {
        return $this->symbolicImageResolver
            ->getSymbolicImage($teaser->resource, $args);
    }

    public function getOpensNewWindow(
        ArticleTeaser $teaser,
    ): bool {
        return $this->opensNewWindowResolver->getOpensNewWindow($teaser->resource);
    }
}
