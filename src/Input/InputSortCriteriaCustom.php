<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\SortDirection;
use Overblog\GraphQLBundle\Annotation as GQL;

class InputSortCriteriaCustom
{
    #[GQL\Field(type: "String!")]
    public ?string $field = null;

    #[GQL\Field(type: "SortDirection!")]
    public ?SortDirection $direction = null;
}
