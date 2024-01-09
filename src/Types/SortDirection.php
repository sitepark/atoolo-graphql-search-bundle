<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum]
enum SortDirection
{
    case ASC;
    case DESC;
}
