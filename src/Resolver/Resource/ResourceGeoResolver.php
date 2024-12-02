<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Geo;
use Atoolo\GraphQL\Search\Types\GeoPoint;
use Atoolo\Resource\Resource;

/**
 * @phpstan-type GeoData array{
 *      features?: array{
 *          primary?: array<array{
 *             geometry?: array{
 *                coordinates?: array<float>
 *             }
 *          }>,
 *          secondary?: array<string,array<string,mixed>>
 *      },
 *      distance?: float
 *  }
 */
class ResourceGeoResolver implements Resolver
{
    public function __construct(
    ) {}

    public function getGeo(
        Resource $resource,
    ): ?Geo {

        /** @var GeoData $geo */
        $geo = $resource->data->getAssociativeArray('base.geo');

        if (empty($geo)) {
            return null;
        }

        $primaryPoint = $this->getPrimaryPoint($geo);
        $secondary = $this->getSecondaryGeoJson($geo);

        return new Geo(
            primary: $primaryPoint,
            secondary: $secondary,
            distance: $geo['distance'] ?? null,
        );
    }

    /**
     * @param GeoData $geo
     */
    private function getPrimaryPoint(array $geo): ?GeoPoint
    {
        if (!isset($geo['features']['primary'])) {
            return null;
        }

        $primary = array_values($geo['features']['primary']);
        if (empty($primary)) {
            return null;
        }

        if (!isset($primary[0]['geometry']['coordinates'])) {
            return null;
        }
        $coordinates  = $primary[0]['geometry']['coordinates'];

        if (count($coordinates) < 2) {
            return null;
        }

        return new GeoPoint(
            $coordinates[0],
            $coordinates[1],
        );
    }

    /**
     * @param GeoData $geo
     * @return array<string,mixed>|null
     */
    private function getSecondaryGeoJson(array $geo): ?array
    {

        if (!isset($geo['features']['secondary'])) {
            return null;
        }

        $seondaryFeatures = array_values($geo['features']['secondary']);
        return [
            'type' => 'FeatureCollection',
            'features' => $seondaryFeatures,
        ];
    }
}
