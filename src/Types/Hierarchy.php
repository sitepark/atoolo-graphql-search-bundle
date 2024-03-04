<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class Hierarchy
{
    public function __construct(
        public readonly string $type,
        public readonly \Atoolo\Resource\Resource $resource
    ) {
    }
}
