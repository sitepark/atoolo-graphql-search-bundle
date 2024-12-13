<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\Search\Dto\Search\Query\GeoPoint;
use InvalidArgumentException;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: "InputGeoPoint")]
class InputGeoPoint
{
    #[GQL\Field(type: "Float!")]
    public ?float $lng;

    #[GQL\Field(type: "Float!")]
    public ?float $lat;

    public function toGeoPoint(): GeoPoint
    {
        if (!isset($this->lng)) {
            throw new InvalidArgumentException(
                'Longitude is required for geo point',
            );
        }
        if (!isset($this->lat)) {
            throw new InvalidArgumentException(
                'Latitude is required for geo point',
            );
        }

        return new GeoPoint(
            lng: $this->lng,
            lat: $this->lat,
        );
    }
}
