<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\AstToArrayConverter;
use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\EnumValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\NullValueNode;
use GraphQL\Language\AST\ObjectFieldNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AstToArrayConverter::class)]
class AstToArrayConverterTest extends TestCase
{
    public function testConvert(): void
    {

        $booleanValueNode = new BooleanValueNode([]);
        $booleanValueNode->value = true;

        $enumValueNode = new EnumValueNode([]);
        $enumValueNode->value = 'abc';

        $intValueNode = new IntValueNode([]);
        $intValueNode->value = '123';

        $floatValueNode = new FloatValueNode([]);
        $floatValueNode->value = '123.1';

        $nullValueNode = new NullValueNode([]);

        $innerObjectStringFieldNode = new ObjectFieldNode([]);
        $innerObjectStringFieldNode->name = new NameNode([]);
        $innerObjectStringFieldNode->name->value = 'myinnerfield';
        $innerObjectStringFieldNode->value = new StringValueNode(['value' => 'myinnervalue']);
        $innerObjectNode = new ObjectValueNode([]);
        $innerObjectNode->fields = new NodeList([$innerObjectStringFieldNode]);

        $listValueNode = new ListValueNode([]);
        $listValueNode->values = new NodeList([
            $booleanValueNode,
            $intValueNode,
            $floatValueNode,
            $nullValueNode,
            $enumValueNode,
            $innerObjectNode,
        ]);

        $objectStringFieldNode = new ObjectFieldNode([]);
        $objectStringFieldNode->name = new NameNode([]);
        $objectStringFieldNode->name->value = 'myfield';
        $objectStringFieldNode->value = new StringValueNode(['value' => 'myvalue']);

        $objectListFieldNode = new ObjectFieldNode([]);
        $objectListFieldNode->name = new NameNode([]);
        $objectListFieldNode->name->value = 'mylist';
        $objectListFieldNode->value = $listValueNode;

        $objectFieldList = new NodeList([$objectStringFieldNode, $objectListFieldNode]);
        $valueNode = new ObjectValueNode([]);
        $valueNode->fields = $objectFieldList;

        $value = AstToArrayConverter::convert($valueNode);
        $expected = [
            'myfield' => 'myvalue',
            'mylist' => [true, 123, 123.1, null, 'abc', ['myinnerfield' => 'myinnervalue']],
        ];
        $this->assertEquals($expected, $value, 'Parsed value does not match expected');
    }

    public function testUnhandledAstNode(): void
    {

        $objectStringFieldNode = new ObjectFieldNode([]);
        $objectStringFieldNode->name = new NameNode([]);
        $objectStringFieldNode->name->value = 'myfield';
        $objectStringFieldNode->value = new TestAstNode([]);

        $objectFieldList = new NodeList([$objectStringFieldNode]);
        $valueNode = new ObjectValueNode([]);
        $valueNode->fields = $objectFieldList;


        $this->expectException(InvalidArgumentException::class);
        AstToArrayConverter::convert($valueNode);
    }
}
