<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\Resource\Resource;

class ResourceOpensNewWindowResolver
{
    public function getOpensNewWindow(
        Resource $resource,
    ): bool {
        return $resource->data->getBool('external.newWindow', false);
    }
}
