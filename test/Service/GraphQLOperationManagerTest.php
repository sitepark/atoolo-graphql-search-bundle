<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Service;

use Atoolo\GraphQL\Search\Dto\GraphQLOperation;
use Atoolo\GraphQL\Search\Service\GraphQLOperationManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GraphQLOperationManager::class)]
class GraphQLOperationManagerTest extends TestCase
{
    private GraphQLOperationManager $manager;

    private array $operationA = [
        'name' => 'operationA',
        'source' => 'query operationA($input: SearchInput!){ search(input: $input) { id } }',
        'sourceLocation' => 'path/to/query/fileA.graphql',
    ];

    private array $operationB = [
        'name' => 'operationB',
        'source' => 'query operationA($input: SearchInput!){ search(input: $input) { name } }',
        'sourceLocation' => 'path/to/query/fileB.graphql',
    ];

    public function setUp(): void
    {
        $this->manager = new GraphQLOperationManager(
            [
                $this->operationA['name'] => $this->operationA,
                $this->operationB['name'] => $this->operationB,
            ],
        );
    }

    public function testHasOperationTrue(): void
    {
        $this->assertTrue(
            $this->manager->hasOperation('operationA'),
        );
    }

    public function testHasOperationFalse(): void
    {
        $this->assertFalse(
            $this->manager->hasOperation('operationXYZ'),
        );
    }

    public function testGetOperationNames(): void
    {
        $this->assertEquals(
            ['operationA', 'operationB'],
            $this->manager->getOperationNames(),
        );
    }

    public function testGetOperation(): void
    {
        $this->assertEquals(
            new GraphQLOperation(
                $this->operationA['name'],
                $this->operationA['source'],
                $this->operationA['sourceLocation'],
            ),
            $this->manager->getOperation('operationA'),
        );
    }

    public function testGetOperations(): void
    {
        $this->assertEqualsCanonicalizing(
            [
                new GraphQLOperation(
                    $this->operationA['name'],
                    $this->operationA['source'],
                    $this->operationA['sourceLocation'],
                ),
                new GraphQLOperation(
                    $this->operationB['name'],
                    $this->operationB['source'],
                    $this->operationB['sourceLocation'],
                ),
            ],
            $this->manager->getOperations(),
        );
    }
}
