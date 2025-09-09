<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use GraphQL\Language\AST\Node;
use InvalidArgumentException;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * This is specified by overblog/GraphQLBundle
 * https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/type-system/scalars.md#custom-scalar
 */
#[GQL\Scalar('SignedDateInterval')]
#[GQL\Description('The scalar type `SignedDateInterval` represents a date interval duration string that additionally allows for a leading minus sign (as specified in ISO 8601-2:2019).')]
class SignedDateInterval
{
    public static function serialize(\DateInterval $interval): string
    {
        $specString = \Atoolo\GraphQL\Search\Types\DateInterval::serialize($interval);
        if ($interval->invert === 1) {
            $specString = '-' . $specString;
        }
        return $specString;
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date interval duration.
     */
    public static function parseValue(string $value): \DateInterval
    {
        $hasLeadingMinus = strpos($value, '-') === 0;
        if ($hasLeadingMinus) {
            $value = substr($value, 1);
        }
        $interval = \Atoolo\GraphQL\Search\Types\DateInterval::parseValue($value);
        if ($hasLeadingMinus) {
            $interval->invert = 1;
        }
        return $interval;
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date interval duration.
     */
    public static function parseLiteral(Node $valueNode): \DateInterval
    {
        if (!property_exists($valueNode, 'value')) {
            throw new \InvalidArgumentException(
                'expected node with string value, got ' . get_class($valueNode),
            );
        }
        $value = $valueNode->value;
        return self::parseValue($value);
    }
}
