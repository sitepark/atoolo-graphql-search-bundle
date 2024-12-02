<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Enum]
enum SpatialOrbitalMode: string
{
    case GREAT_CIRCLE_DISTANCE = 'great-circle-distance';
    case BOUNDING_BOX = 'bounding-box';
}
