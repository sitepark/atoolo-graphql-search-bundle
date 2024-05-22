<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

interface TeaserFactory
{
    public function create(Resource $resource): Teaser;
}
