<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Enum]
enum QueryOperator: string
{
    case AND = 'AND';
    case OR = 'OR';
}
