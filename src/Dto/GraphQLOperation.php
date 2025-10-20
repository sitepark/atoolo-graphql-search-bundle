<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Dto;

/**
 * @codeCoverageIgnore
 */
class GraphQLOperation
{
    public function __construct(
        public readonly string $name,
        public readonly string $source,
        public readonly string $sourceLocation,
    ) {}
}
