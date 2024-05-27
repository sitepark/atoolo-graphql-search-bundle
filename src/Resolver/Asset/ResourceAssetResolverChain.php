<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ResourceAssetResolverChain implements Resolver
{
    /**
     * @var array<string, ResourceAssetResolver>
     */
    private readonly array $regularAssetResolvers;

    /**
     * @var array<string, ResourceAssetResolver>
     */
    private readonly array $symbolicAssetResolvers;

    /**
     * @param array<string, ResourceAssetResolver> $regularAssetResolvers
     * @param array<string, ResourceAssetResolver> $symbolicAssetResolvers
     */
    public function __construct(
        iterable $regularAssetResolvers,
        iterable $symbolicAssetResolvers
    ) {
        $this->regularAssetResolvers =
            $regularAssetResolvers instanceof \Traversable ?
            iterator_to_array($regularAssetResolvers) :
            $regularAssetResolvers;
        $this->symbolicAssetResolvers =
            $symbolicAssetResolvers instanceof \Traversable ?
            iterator_to_array($symbolicAssetResolvers) :
            $symbolicAssetResolvers;
    }

    /**
     * Iterates over all regular asset resolvers (tagged with
     * `atoolo_graphql_search.resolver.asset.regular`) and all
     * symbolic asset resolvers (tagged with
     * `atoolo_graphql_search.resolver.asset.symbolic`) and returns
     * the first resolved asset that is not null. All  regular asset
     * resolvers will always have priority over all symbolic asset resolvers.
     * Passing the argument `forceSymbolic` will cause this method to
     * only apply symbolic asset resolvers, ignoring the regular assert
     * resolvers.
     */
    public function getAsset(
        Resource $resource,
        ArgumentInterface $args
    ): ?Asset {
        if ($args['forceSymbolic'] ?? false) {
            return $this->getAssetSymbolic($resource, $args);
        }
        return $this->getAssetRegular($resource, $args)
            ?? $this->getAssetSymbolic($resource, $args);
    }

    public function getAssetRegular(
        Resource $resource,
        ArgumentInterface $args
    ): ?Asset {
        foreach ($this->regularAssetResolvers as $regularAssetResolver) {
            $asset = $regularAssetResolver->getAsset(
                $resource,
                $args
            );
            if ($asset !== null) {
                return $asset;
            }
        }
        return null;
    }

    public function getAssetSymbolic(
        Resource $resource,
        ArgumentInterface $args
    ): ?Asset {
        foreach ($this->symbolicAssetResolvers as $symbolicAssetResolvers) {
            $asset = $symbolicAssetResolvers->getAsset(
                $resource,
                $args
            );
            if ($asset !== null) {
                return $asset;
            }
        }
        return null;
    }
}
