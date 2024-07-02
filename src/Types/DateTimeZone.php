<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Exception;
use GraphQL\Language\AST\Node;
use InvalidArgumentException;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * This is specified by overblog/GraphQLBundle
 * https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/type-system/scalars.md#custom-scalar
 */
#[GQL\Scalar('DateTimeZone')]
class DateTimeZone
{
    public static function serialize(\DateTimeZone $value): string
    {
        return $value->getName();
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  timezone string.
     */
    public static function parseValue(string $value): \DateTimeZone
    {
        return self::parseTimeZone($value);
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  timezone string.
     */
    public static function parseLiteral(Node $valueNode): \DateTimeZone
    {
        if (!property_exists($valueNode, 'value')) {
            throw new \InvalidArgumentException(
                'expected node with string value, got ' . get_class($valueNode),
            );
        }
        $value = $valueNode->value;
        return self::parseTimeZone($value);
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  timezone string.
     */
    private static function parseTimeZone(string $value): \DateTimeZone
    {
        try {
            return new \DateTimeZone($value);
        } catch (Exception $e) {
            throw new InvalidArgumentException(
                'Invalid timezone, got ' . $value,
            );
        }
    }
}
