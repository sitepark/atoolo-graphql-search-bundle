<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Dto;

use GraphQL\Error\Error;

/**
 * @codeCoverageIgnore
 */
class GraphQLResult
{
    /**
     * @param array<string,mixed> $data
     * @param array<int, Error> $errors
     * @param array<string,mixed> $extensions
     */
    public function __construct(
        public readonly array $data = [],
        public readonly array $errors = [],
        public readonly array $extensions = [],
    ) {}
}
