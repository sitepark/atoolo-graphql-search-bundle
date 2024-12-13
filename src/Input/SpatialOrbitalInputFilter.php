<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\SpatialOrbitalMode;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "SpatialOrbitalInputFilter")]
class SpatialOrbitalInputFilter
{
    #[GQL\Field(type: "Float!")]
    public ?float $distance = null;

    #[GQL\Field(type: "InputGeoPoint!")]
    public ?InputGeoPoint $centerPoint = null;

    #[GQL\Field(type: "SpatialOrbitalMode")]
    public ?SpatialOrbitalMode $mode = null;
}
