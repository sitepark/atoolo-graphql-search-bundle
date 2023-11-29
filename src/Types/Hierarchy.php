<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

class Hierarchy
{
    public function __construct(
        private readonly string $type,
        private readonly \Atoolo\Resource\Resource $resource
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResource(): \Atoolo\Resource\Resource
    {
        return $this->resource;
    }
}
