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
#[GQL\Scalar('DateInterval')]
#[GQL\Description('The scalar type `DateInterval` represents a date interval duration string. It is specified in ISO-8601 format (e.g. `P3Y6M4DT12H30M5S`).')]
class DateInterval
{
    public static function serialize(\DateInterval $interval): string
    {
        // Reading all non-zero date parts.
        $date = array_filter(
            [
                'Y' => $interval->y,
                'M' => $interval->m,
                'D' => $interval->d,
            ],
        );

        // Reading all non-zero time parts.
        $time = array_filter(
            [
                'H' => $interval->h,
                'M' => $interval->i,
                'S' => $interval->s,
            ],
        );

        $specString = 'P';

        // Adding each part to the spec-string.
        foreach ($date as $key => $value) {
            $specString .= $value . $key;
        }
        if (count($time) > 0) {
            $specString .= 'T';
            foreach ($time as $key => $value) {
                $specString .= $value . $key;
            }
        }

        return $specString;
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date interval duration.
     */
    public static function parseValue(string $value): \DateInterval
    {
        return self::parseInterval($value);
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
        return self::parseInterval($value);
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date interval duration.
     */
    private static function parseInterval(string $value): \DateInterval
    {
        try {
            return new \DateInterval($value);
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                'Invalid DateInterval string: ' . $value,
                0,
                $e,
            );
        }
    }
}
