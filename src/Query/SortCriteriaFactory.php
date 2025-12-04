<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\Sort\Criteria;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use Atoolo\Search\Dto\Search\Query\Sort\SpatialDist;
use InvalidArgumentException;

class SortCriteriaFactory
{
    public function create(InputSortCriteria $criteria): Criteria
    {
        if (isset($criteria->name)) {
            $direction = $this->mapDirection($criteria->name);
            return new Name($direction);
        }

        if (isset($criteria->date)) {
            $direction = $this->mapDirection($criteria->date);
            return new Date($direction);
        }

        if (isset($criteria->natural)) {
            $direction = $this->mapDirection($criteria->natural);
            return new Natural($direction);
        }

        if (isset($criteria->score)) {
            $direction = $this->mapDirection($criteria->score);
            return new Score($direction);
        }

        if (isset($criteria->spatialDist)) {
            if ($criteria->spatialDist->direction === null) {
                throw new InvalidArgumentException('direction is required for sort criteria spatialDist');
            }
            if ($criteria->spatialDist->spatialPoint === null) {
                throw new InvalidArgumentException('spatialPoint is required for sort criteria spatialDist');
            }
            $direction = $this->mapDirection($criteria->spatialDist->direction);
            return new SpatialDist($direction, $criteria->spatialDist->spatialPoint->toGeoPoint());
        }

        throw new InvalidArgumentException('Sort criteria not found');
    }

    private function mapDirection(SortDirection $direction): Direction
    {
        return $direction === SortDirection::ASC
            ? Direction::ASC
            : Direction::DESC;
    }
}
