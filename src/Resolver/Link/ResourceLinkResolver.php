<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Link;

use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;

interface ResourceLinkResolver
{
    public function getLink(Resource $resource): ?Link;
}
