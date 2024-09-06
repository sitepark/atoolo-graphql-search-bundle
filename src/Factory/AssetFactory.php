<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\Resource\Resource;

interface AssetFactory
{
    public function create(
        Resource $resource,
        ?string $variant = null,
    ): ?Asset;
}
