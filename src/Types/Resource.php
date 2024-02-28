<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

class Resource
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $location
    ) {
    }
}
