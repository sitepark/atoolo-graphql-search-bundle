<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: "SpatialDistanceRangeInputFacet")]
class SpatialDistanceRangeInputFacet
{
    #[GQL\Field(type: "InputGeoPoint!")]
    public ?InputGeoPoint $point = null;

    #[GQL\Field(type: "Float!")]
    public float $from;

    #[GQL\Field(type: "Float!")]
    public float $to;
}
