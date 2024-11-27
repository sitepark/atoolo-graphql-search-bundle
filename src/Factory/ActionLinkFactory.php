<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;

interface ActionLinkFactory
{
    /**
     * @return Link[]
     */
    public function create(
        Resource $resource,
    ): array;
}
