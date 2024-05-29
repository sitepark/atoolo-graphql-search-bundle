<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Asset;

use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ResourceAssetResolverChain implements ResourceAssetResolver, ResourceSymbolicImageResolver
{
    /**
     * @param array<ResourceAssetResolver> $resolvers
     */
    public function __construct(
        private readonly iterable $resolvers
    ) {
    }

    public function getAsset(
        Resource $resource,
        ArgumentInterface $args
    ): ?Asset {
        foreach ($this->resolvers as $resolver) {
            $asset = $resolver->getAsset(
                $resource,
                $args
            );
            if ($asset !== null) {
                return $asset;
            }
        }
        return null;
    }

    public function getSymbolicImage(
        Resource $resource,
        ArgumentInterface $args
    ): ?SymbolicImage {
        foreach ($this->resolvers as $resolver) {
            if (!$resolver instanceof ResourceSymbolicImageResolver) {
                continue;
            }
            $symbolicImage = $resolver->getSymbolicImage(
                $resource,
                $args
            );
            if ($symbolicImage !== null) {
                return $symbolicImage;
            }
        }
        return null;
    }
}
