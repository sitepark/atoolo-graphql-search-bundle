<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\GraphQL\Search\Types\TeaserFeature;
use Atoolo\Resource\Resource;

interface TeaserFeatureFactory
{
    /**
     * @return TeaserFeature[]
     */
    public function create(
        Resource $resource,
    ): array;
}
