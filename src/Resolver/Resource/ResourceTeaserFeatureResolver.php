<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\TeaserFeatureFactory;
use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\TeaserFeature;
use Atoolo\Resource\Resource;

class ResourceTeaserFeatureResolver implements Resolver
{
    /**
     * @param array<TeaserFeatureFactory> $factories
     */
    public function __construct(
        private readonly iterable $factories,
    ) {}

    /**
     * @return TeaserFeature[]
     */
    public function getTeaserFeatures(
        Resource $resource,
    ): array {
        $links = [];
        foreach ($this->factories as $factory) {
            foreach ($factory->create($resource) as $createdFeature) {
                if ($createdFeature !== null) {
                    $links[] = $createdFeature;
                }
            }
        }
        return $links;
    }
}
