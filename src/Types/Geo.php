<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class Geo
{
    /**
     * @param array<string,mixed>|null $secondary
     */
    public function __construct(
        public readonly ?GeoPoint $primary,
        public readonly ?array $secondary,
        public readonly ?float $distance = null,
    ) {}
}
