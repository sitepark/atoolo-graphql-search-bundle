<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

class Hierarchy
{
    public function __construct(
        public readonly string $type,
        public readonly \Atoolo\Resource\Resource $resource
    ) {
    }
}
