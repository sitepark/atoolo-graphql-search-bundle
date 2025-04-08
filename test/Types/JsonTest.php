<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\Json;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectFieldNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Json::class)]
class JsonTest extends TestCase
{
    public function testSerialize(): void
    {
        self::assertEquals(
            ['data' => 'x'],
            Json::serialize(['data' => 'x']),
        );
    }

    public function testParseValue(): void
    {
        self::assertEquals(
            ['data' => 'x'],
            Json::parseValue(['data' => 'x']),
        );
    }

    public function testParseLiteral(): void
    {
        $fieldNode = new ObjectFieldNode([]);
        $fieldNode->name = new NameNode([]);
        $fieldNode->name->value = 'myfield';
        $fieldNode->value = new StringValueNode(['value' => 'myvalue']);

        $nodeList = new NodeList([$fieldNode]);
        $valueNode = new ObjectValueNode([]);
        $valueNode->fields = $nodeList;

        $value = Json::parseLiteral($valueNode);
        $this->assertEquals(['myfield' => 'myvalue'], $value, 'Parsed value does not match expected');
    }

    /**
     * @throws JsonException
     */
    public function testParseLiteralWithInvalidNode(): void
    {
        $valueNode = new StringValueNode(['value' => 'abc']);
        $this->expectException(InvalidArgumentException::class);
        Json::parseLiteral($valueNode);
    }
}
