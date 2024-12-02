<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class GeoPoint
{
    public function __construct(
        public readonly float $lng,
        public readonly float $lat,
    ) {}
}
