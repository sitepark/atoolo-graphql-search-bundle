<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputGeoPoint;
use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Query\SortCriteriaFactory;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\GeoPoint;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use Atoolo\Search\Dto\Search\Query\Sort\SpatialDist;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SortCriteriaFactory::class)]
class SortCriteriaFactoryTest extends TestCase
{
    public function testCreateWithSortName(): void
    {
        $criteria = $this->createSearchInputWithSort('name');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Name(Direction::ASC),
            $sort,
            'name sort expected',
        );
    }

    public function testCreateWithSortDate(): void
    {
        $criteria = $this->createSearchInputWithSort('date');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Date(Direction::ASC),
            $sort,
            'date sort expected',
        );
    }

    public function testCreateWithSortNatural(): void
    {
        $criteria = $this->createSearchInputWithSort('natural');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Natural(Direction::ASC),
            $sort,
            'natural sort expected',
        );
    }

    public function testCreateWithSortScore(): void
    {
        $criteria = $this->createSearchInputWithSort('score');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Score(Direction::ASC),
            $sort,
            'score sort expected',
        );
    }

    public function testCreateWithSpatialDist(): void
    {
        $criteria = $this->createSearchInputWithSort('spatialDist');
        $criteria->spatialPoint = new InputGeoPoint();
        $criteria->spatialPoint->lng = 1;
        $criteria->spatialPoint->lat = 2;

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new SpatialDist(Direction::ASC, new GeoPoint(1, 2)),
            $sort,
            'spatialDist sort expected',
        );
    }

    public function testCreateWithSpatialDistAndMissingSpatialPoint(): void
    {
        $criteria = $this->createSearchInputWithSort('spatialDist');

        $factory = new SortCriteriaFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create($criteria);
    }

    public function testCreateWithInvalidSort(): void
    {
        $sort = new InputSortCriteria();

        $factory = new SortCriteriaFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create($sort);
    }

    private function createSearchInputWithSort(string $name): InputSortCriteria
    {
        $sort = new InputSortCriteria();
        $sort->$name = SortDirection::ASC;
        return $sort;
    }
}
