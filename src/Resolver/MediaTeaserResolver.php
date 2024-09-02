<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\Asset\ResourceAssetResolver;
use Atoolo\GraphQL\Search\Resolver\Asset\ResourceSymbolicImageResolver;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class MediaTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ResourceAssetResolver $assetResolver,
        private readonly ResourceSymbolicImageResolver $symbolicImageResolver,
        private readonly ResourceKickerResolver $kickerResolver,
        private readonly ResourceOpensNewWindowResolver $opensNewWindowResolver,
    ) {}

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

    public function getSymbolicImage(
        MediaTeaser $teaser,
        ArgumentInterface $args,
    ): ?SymbolicImage {
        return $this->symbolicImageResolver
            ->getSymbolicImage($teaser->resource, $args);
    }

    public function getOpensNewWindow(
        MediaTeaser $teaser,
    ): bool {
        return $this->opensNewWindowResolver->getOpensNewWindow($teaser->resource);
    }
}
