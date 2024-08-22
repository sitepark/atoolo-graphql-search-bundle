<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\Resource\Resource;

class ResourceLinkNewWindowResolver
{
    public function getLinkNewWindow(
        Resource $resource,
    ): bool {
        return $resource->data->getBool('external.newWindow', false);
    }
}
