<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

abstract class Teaser
{
    public function __construct(
        public readonly ?string $url
    ) {
    }
}
