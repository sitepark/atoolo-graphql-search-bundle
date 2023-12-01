<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;


enum QueryDefaultOperator
{
    case AND;
    case OR;
}
