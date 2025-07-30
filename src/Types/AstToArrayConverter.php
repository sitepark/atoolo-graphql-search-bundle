<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use GraphQL\Language\AST\{BooleanValueNode,
    EnumValueNode,
    FloatValueNode,
    IntValueNode,
    ListValueNode,
    Node,
    NullValueNode,
    ObjectValueNode,
    StringValueNode};

class AstToArrayConverter
{
    /**
     * @return array<string,mixed>
     */
    public static function convert(ObjectValueNode $object): array
    {
        $result = [];
        foreach ($object->fields as $field) {
            $name = $field->name->value;
            $value = self::extract($field->value);
            $result[$name] = $value;
        }
        return $result;
    }

    /**
     * @return array<mixed>|string|bool|null
     */
    private static function extract(Node $node): array|string|bool|null
    {
        return match (get_class($node)) {
            IntValueNode::class, FloatValueNode::class => $node->value,
            StringValueNode::class, BooleanValueNode::class,
            EnumValueNode::class => $node->value,
            NullValueNode::class => null,
            ListValueNode::class => array_map([self::class, 'extract'], iterator_to_array($node->values)),
            ObjectValueNode::class => self::convert($node),
            default => throw new \InvalidArgumentException('Unhandled AST node: ' . get_class($node)),
        };
    }
}
