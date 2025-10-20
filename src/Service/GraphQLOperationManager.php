<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Service;

use Atoolo\GraphQL\Search\Dto\GraphQLOperation;

/**
 * This service holds all available graphql operations that are defined in .graphql files.
 *
 * @phpstan-type RawOperation array{name: string, source: string, sourceLocation: string}
 */
class GraphQLOperationManager
{
    /**
     * @var GraphQLOperation[] $operations
     */
    private readonly array $operations;

    /**
     * @param array<string,RawOperation> $operationsRaw Pre-loaded GraphQL operations keyed by their name
     */
    public function __construct(
        array $operationsRaw,
    ) {
        $operations = [];
        foreach ($operationsRaw as $operationName => $operationRaw) {
            $operations[$operationName] = new GraphQLOperation(
                $operationRaw['name'],
                $operationRaw['source'],
                $operationRaw['sourceLocation'],
            );
        }
        $this->operations = $operations;
    }

    /**
     * Checks wether a pre-loaded GraphQL operation exists or not
     */
    public function hasOperation(string $operationName): bool
    {
        return isset($this->operations[$operationName]);
    }

    /**
     * Returns the names of all available operations
     * @return string[]
     */
    public function getOperationNames(): array
    {
        return array_keys($this->operations);
    }

    public function getOperation(string $operationName): GraphQLOperation
    {
        return $this->operations[$operationName];
    }

    /**
     * @return array<string,GraphQLOperation> operations keyed by their name
     */
    public function getOperations(): array
    {
        return $this->operations;
    }
}
