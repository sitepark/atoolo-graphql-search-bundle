<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\SortDirection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name:"InputSortCriteria")]
class InputSortCriteria
{
    #[GQL\Field(type:"SortDirection")]
    public ?SortDirection $name;

    #[GQL\Field(type:"SortDirection")]
    public ?SortDirection $headline;

    #[GQL\Field(type:"SortDirection")]
    public ?SortDirection $date;

    #[GQL\Field(type:"SortDirection")]
    public ?SortDirection $natural;

    #[GQL\Field(type:"SortDirection")]
    public ?SortDirection $score;
}
