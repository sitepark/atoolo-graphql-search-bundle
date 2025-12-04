<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\SortDirection;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "InputSortCriteria")]
class InputSortCriteria
{
    #[GQL\Field(type: "SortDirection")]
    public ?SortDirection $name = null;

    #[GQL\Field(type: "SortDirection")]
    public ?SortDirection $date = null;

    #[GQL\Field(type: "SortDirection")]
    public ?SortDirection $natural = null;

    #[GQL\Field(type: "SortDirection")]
    public ?SortDirection $score = null;

    #[GQL\Field(type: "InputSortCriteriaSpatialDist")]
    public ?InputSortCriteriaSpatialDist $spatialDist = null;

    #[GQL\Field(type: "InputSortCriteriaCustom")]
    public ?InputSortCriteriaCustom $custom = null;

}
