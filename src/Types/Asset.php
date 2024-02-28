<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

abstract class Asset
{
    public function __construct(
        public readonly ?string $copyright,
        public readonly ?string $caption,
        public readonly ?string $description
    ) {
    }
}
