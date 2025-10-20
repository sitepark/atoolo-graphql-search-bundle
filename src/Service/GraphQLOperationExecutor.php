<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Service;

use Atoolo\GraphQL\Search\Dto\GraphQLResult;
use Overblog\GraphQLBundle\Request\Executor;

class GraphQLOperationExecutor
{
    public function __construct(
        private readonly Executor $executor,
        private readonly GraphQLOperationManager $graphQLOperationManager,
    ) {}

    /**
     * Checks wether a pre-loaded GraphQL operation exists or not
     */
    public function hasOperation(string $operationName): bool
    {
        return $this->graphQLOperationManager->hasOperation($operationName);
    }

    /**
     * Executes a pre-loaded GraphQL operation by its name.
     *
     * @param string $operationName The unique name of the operation
     * @param array<string, mixed> $variables Query variables
     * @throws \InvalidArgumentException If the requested operation was not found
     */
    public function executeOperation(string $operationName, array $variables = []): GraphQLResult
    {
        if (!$this->hasOperation($operationName)) {
            throw new \InvalidArgumentException(
                sprintf('GraphQL operation "%s" not found. It was not loaded during container compilation.', $operationName),
            );
        }
        return $this->executeQueryString(
            $this->graphQLOperationManager->getOperation($operationName)->source,
            $operationName,
            $variables,
        );
    }

    /**
     * Executes a graphql query by passing the query string directly
     *
     * @param string $queryString graphql query string
     * @param string $operationName operation name of there query,
     * @param array<string,mixed> $variables query variables
     */
    public function executeQueryString(string $queryString, string $operationName, array $variables = []): GraphQLResult
    {
        $overblogResult = $this->executor->execute(
            null,
            [
                'query' => $queryString,
                'variables' => $variables,
                'operationName' => $operationName,
            ],
            null,
        );
        return new GraphQLResult(
            $overblogResult->data ?? [],
            $overblogResult->errors ?? [],
            $overblogResult->extensions ?? [],
        );
    }
}
