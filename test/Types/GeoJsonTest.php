<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\GeoJson;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectFieldNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GeoJson::class)]
class GeoJsonTest extends TestCase
{
    public function testSerialize(): void
    {
        self::assertEquals(
            ['data' => 'x'],
            GeoJson::serialize(['data' => 'x']),
        );
    }

    public function testParseValue(): void
    {
        self::assertEquals(
            ['data' => 'x'],
            GeoJson::parseValue(['data' => 'x']),
        );
    }

    public function testParseLiteral(): void
    {
        $fieldNode = new ObjectFieldNode([]);
        $fieldNode->name = new NameNode([]);
        $fieldNode->name->value = 'data';
        $fieldNode->value = new StringValueNode(['value' => 'data']);

        $nodeList = new NodeList([$fieldNode]);
        $valueNode = new ObjectValueNode([]);
        $valueNode->fields = $nodeList;

        $this->expectExceptionMessage('Not implemented');
        GeoJson::parseLiteral($valueNode);
    }

    /**
     * @throws JsonException
     */
    public function testParseLiteralWithInvalidNode(): void
    {
        $valueNode = new StringValueNode(['value' => 'abc']);
        $this->expectException(InvalidArgumentException::class);
        GeoJson::parseLiteral($valueNode);
    }

}
