<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

interface ResourceAssetResolver extends Resolver
{
    public function getAsset(
        Resource $resource,
        ArgumentInterface $args
    ): ?Asset;
}
