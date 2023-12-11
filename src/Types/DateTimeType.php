<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use DateTime;
use Exception;
use GraphQL\Language\AST\Node;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Scalar('DateTime')]
class DateTimeType
{
    public static function serialize(\DateTime $value): string
    {
        return $value->format(\DateTime::RFC3339);
    }

    /**
     * @throws Exception
     */
    public static function parseValue(string $value)
    {
        return new DateTime($value);
    }

    /**
     * @throws Exception
     */
    public static function parseLiteral(Node $valueNode): DateTime
    {
        return new DateTime($valueNode->value);
    }

}