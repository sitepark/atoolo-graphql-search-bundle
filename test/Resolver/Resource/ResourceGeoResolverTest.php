<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceGeoResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Geo;
use Atoolo\GraphQL\Search\Types\GeoPoint;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceGeoResolver::class)]
class ResourceGeoResolverTest extends TestCase
{
    private ResourceGeoResolver $resolver;

    public function setUp(): void
    {
        $this->resolver = new ResourceGeoResolver();
    }

    public function testWithDistance(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'distance' => 10,
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(null, null, 10),
            $geo,
            'unexpected geo data',
        );
    }

    public function testWithPrimary(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'features' => [
                        'primary' => [
                            [
                                'geometry' => [
                                    'coordinates' => [1, 2],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(new GeoPoint(1, 2), null, 0),
            $geo,
            'unexpected geo data',
        );
    }

    public function testWithPrimaryInvalidCoordinates(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'features' => [
                        'primary' => [
                            [
                                'geometry' => [
                                    'coordinates' => [2],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(null, null, 0),
            $geo,
            'unexpected geo data',
        );
    }

    public function testWithPrimaryMissingCoordinates(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'features' => [
                        'primary' => [
                            [
                                'geometry' => [
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(null, null, 0),
            $geo,
            'unexpected geo data',
        );
    }

    public function testWithPrimaryEmptyArray(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'features' => [
                        'primary' => [
                        ],
                    ],
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(null, null, 0),
            $geo,
            'unexpected geo data',
        );
    }

    public function testWithMissingPrimary(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'features' => [
                    ],
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(null, null, 0),
            $geo,
            'unexpected geo data',
        );
    }

    public function testWithSecondary(): void
    {
        $resource = $this->createResource([
            'base' => [
                'geo' => [
                    'features' => [
                        'secondary' => [
                            [
                                'geo' => 'json',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $geo = $this->resolver->getGeo($resource);

        $this->assertEquals(
            new Geo(null, [
                'type' => 'FeatureCollection',
                'features' => [
                    [
                        'geo' => 'json',
                    ],
                ],
            ], 0),
            $geo,
            'unexpected geo data',
        );
    }

    public function testEmpty(): void
    {
        $resource = $this->createResource([]);
        $geo = $this->resolver->getGeo($resource);

        $this->assertNull(
            $geo,
            'unexpected geo data',
        );
    }


    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
