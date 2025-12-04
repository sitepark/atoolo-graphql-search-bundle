<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\SortDirection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: "InputSortCriteriaSpatialDist")]
class InputSortCriteriaSpatialDist
{
    #[GQL\Field(type: "InputGeoPoint!")]
    public ?InputGeoPoint $spatialPoint = null;

    #[GQL\Field(type: "SortDirection!")]
    public ?SortDirection $direction = null;
}
