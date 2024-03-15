<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use DateMalformedStringException;
use DateTimeInterface;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * This is specified by overblog/GraphQLBundle
 * https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/type-system/scalars.md#custom-scalar
 */
#[GQL\Scalar('DateTime')]
class DateTime
{
    public static function serialize(\DateTime $value): string
    {
        return $value->format(DateTimeInterface::RFC3339);
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function parseValue(string $value): \DateTime
    {
        return new \DateTime($value);
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function parseLiteral(Node $valueNode): \DateTime
    {
        $value = $valueNode->value; // @phpstan-ignore-line
        return new \DateTime($value);
    }
}
