<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\AssetFactory;
use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ResourceSymbolicAssetResolver implements Resolver
{
    public function __construct(
        private readonly AssetFactory $symbolicAssetFactory,
    ) {}

    public function getSymbolicAsset(
        Resource $resource,
        ArgumentInterface $args,
    ): ?Asset {
        if (isset($args['variant']) && !is_string($args['variant'])) {
            throw new \InvalidArgumentException(
                'argument \'variant\' must be of type string',
            );
        }
        /** @var ?string $variant */
        $variant = $args['variant'] ?? null;
        $asset = $this->symbolicAssetFactory->create(
            $resource,
            $variant,
        );
        return $asset;
    }
}
