<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Input;

use Atoolo\GraphQL\Search\Input\InputGeoPoint;
use Atoolo\Search\Dto\Search\Query\GeoPoint;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InputGeoPoint::class)]
class InputGeoPointTest extends TestCase
{
    public function testToGeoPoint(): void
    {
        $input = new InputGeoPoint();
        $input->lng = 1;
        $input->lat = 2;

        $geoPoint = $input->toGeoPoint();

        $this->assertEquals(
            new GeoPoint(1, 2),
            $geoPoint,
            'geoPoint expected',
        );
    }

    public function testToGeoPointWithMissingLng(): void
    {
        $input = new InputGeoPoint();
        $input->lat = 2;

        $this->expectException(InvalidArgumentException::class);
        $input->toGeoPoint();
    }

    public function testToGeoPointWithMissingLat(): void
    {
        $input = new InputGeoPoint();
        $input->lng = 1;

        $this->expectException(InvalidArgumentException::class);
        $input->toGeoPoint();
    }

}
