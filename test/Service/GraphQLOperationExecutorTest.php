<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Service;

use Atoolo\GraphQL\Search\Dto\GraphQLOperation;
use Atoolo\GraphQL\Search\Service\GraphQLOperationExecutor;
use Atoolo\GraphQL\Search\Service\GraphQLOperationManager;
use GraphQL\Executor\ExecutionResult;
use Overblog\GraphQLBundle\Request\Executor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(GraphQLOperationExecutor::class)]
class GraphQLOperationExecutorTest extends TestCase
{
    private GraphQLOperationExecutor $executor;

    private Executor&MockObject $overblogExecutor;

    private GraphQLOperationManager&MockObject $graphQLOperationManager;

    private GraphQLOperation $testOperation;

    public function setUp(): void
    {
        $this->testOperation = new GraphQLOperation(
            'testOperation',
            'query testOperation{ someQuery }',
            'path/to/query/file.graphql',
        );

        $this->overblogExecutor = $this->createStub(Executor::class);
        $this->graphQLOperationManager = $this->createMock(GraphQLOperationManager::class);
        $this->graphQLOperationManager
            ->method('hasOperation')
            ->willReturnCallback(fn($name) => $name === $this->testOperation->name);
        $this->graphQLOperationManager
            ->method('getOperation')
            ->with($this->testOperation->name)
            ->willReturn($this->testOperation);

        $this->executor = new GraphQLOperationExecutor(
            $this->overblogExecutor,
            $this->graphQLOperationManager,
        );
    }

    public function testHasOperation(): void
    {
        $this->assertTrue(
            $this->executor->hasOperation($this->testOperation->name),
        );
    }

    public function testExecuteOperation(): void
    {
        $variables = [
            'variableA' => 12,
            'variableB' => false,
        ];
        $fictionalGraphqlResult =  new ExecutionResult(
            [
                'someQuery' => [
                    'id' => 13435,
                ],
            ],
        );
        $this->overblogExecutor
            ->method('execute')
            ->with(
                null,
                [
                    'query' => $this->testOperation->source,
                    'variables' => $variables,
                    'operationName' => $this->testOperation->name,
                ],
                null,
            )
            ->willReturn($fictionalGraphqlResult);

        $this->assertEquals(
            new ExecutionResult(
                $fictionalGraphqlResult->data,
            ),
            $this->executor->executeOperation($this->testOperation->name, $variables),
        );
    }

    public function testExecuteNonExistingOperation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->executor->executeOperation('nonExistingOperation');
    }
}
