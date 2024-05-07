<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Enum]
enum SortDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}
