<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

interface TeaserResolver
{
    public function accept(Resource $resource): bool;
    public function resolve(Resource $resource): Teaser;
}
