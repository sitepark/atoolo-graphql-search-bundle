<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

interface ResourceSymbolicImageResolver extends Resolver
{
    public function getSymbolicImage(
        Resource $resource,
        ArgumentInterface $args
    ): ?SymbolicImage;
}
