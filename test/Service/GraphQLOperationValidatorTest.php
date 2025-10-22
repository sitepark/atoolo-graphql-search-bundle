<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Service;

use Atoolo\GraphQL\Search\Dto\GraphQLOperation;
use Atoolo\GraphQL\Search\Service\GraphQLOperationValidator;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Overblog\GraphQLBundle\Request\Executor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[CoversClass(GraphQLOperationValidator::class)]
class GraphQLOperationValidatorTest extends TestCase
{
    private GraphQLOperationValidator $validator;

    private Executor&Stub $overblogExecutor;

    public function setUp(): void
    {
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'hello' => [
                    'type' => Type::string(),
                    'resolve' => fn() => 'Hello World!',
                ],
            ],
        ]);
        $schema = new Schema([
            'query' => $queryType,
        ]);
        $this->overblogExecutor = $this->createStub(Executor::class);
        $this->overblogExecutor
            ->method('getSchema')
            ->willReturn($schema);
        $this->validator = new GraphQLOperationValidator($this->overblogExecutor);
    }

    public function testValidateSuccess(): void
    {
        $operation = new GraphQLOperation(
            'sayHelloWorld',
            <<<GRAPHQL
                query sayHelloWorld{
                    hello
                }
            GRAPHQL,
            'some/path/to/file.graphql',
        );
        $this->assertEmpty(
            $this->validator->validate($operation),
        );
    }

    public function testValidateSchemaError(): void
    {
        $operation = new GraphQLOperation(
            'sayGoodbye',
            <<<GRAPHQL
                query sayHelloWorld{
                    goodbye
                }
            GRAPHQL,
            'some/path/to/file.graphql',
        );
        $this->assertNotEmpty(
            $this->validator->validate($operation),
        );
    }

    public function testValidateSyntaxError(): void
    {
        $operation = new GraphQLOperation(
            'sayHelloWorld',
            <<<GRAPHQL
                query sayHelloWorld(a){
                    hello
                }}}
            GRAPHQL,
            'some/path/to/file.graphql',
        );
        $this->assertNotEmpty(
            $this->validator->validate($operation),
        );
    }
}
