<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "SpatialArbitraryRectangleInputFilter")]
class SpatialArbitraryRectangleInputFilter
{
    #[GQL\Field(type: "InputGeoPoint!")]
    public ?InputGeoPoint $lowerLeftCorner = null;

    #[GQL\Field(type: "InputGeoPoint!")]
    public ?InputGeoPoint $upperRightCorner = null;
}
