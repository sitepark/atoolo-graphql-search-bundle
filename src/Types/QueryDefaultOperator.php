<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum]
enum QueryDefaultOperator
{
    case AND;
    case OR;
}
