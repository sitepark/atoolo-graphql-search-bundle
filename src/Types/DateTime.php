<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use DateTimeInterface;
use Exception;
use GraphQL\Language\AST\Node;
use InvalidArgumentException;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * This is specified by overblog/GraphQLBundle
 * https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/type-system/scalars.md#custom-scalar
 */
#[GQL\Scalar('DateTime')]
#[GQL\Description('The scalar type `DateTime` represents a date and time string. It is specified with the UTC time zone in ISO-8601 format (e.g. `2024-05-22T10:13:00Z`).')]
class DateTime
{
    public static function serialize(\DateTime $value): string
    {
        return $value->format(DateTimeInterface::ATOM);
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date/time string.
     */
    public static function parseValue(string $value): \DateTime
    {
        return self::parseDate($value);
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date/time string.
     */
    public static function parseLiteral(Node $valueNode): \DateTime
    {
        if (!property_exists($valueNode, 'value')) {
            throw new \InvalidArgumentException(
                'expected node with string value, got ' . get_class($valueNode),
            );
        }
        $value = $valueNode->value;
        return self::parseDate($value);
    }

    /**
     * @throws InvalidArgumentException if the given value is not a valid
     *  date/time string.
     */
    private static function parseDate(string $value): \DateTime
    {
        try {
            return new \DateTime($value);
        } catch (Exception $e) {
            throw new InvalidArgumentException(
                'Invalid date/time string, got ' . $value,
            );
        }
    }
}
