<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\Sort\Criteria;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Headline;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use InvalidArgumentException;

class SortCriteriaFactory
{
    public function create(InputSortCriteria $criteria): Criteria
    {
        if (isset($criteria->name)) {
            $direction = $this->mapDirection($criteria->name);
            return new Name($direction);
        }

        if (isset($criteria->headline)) {
            $direction = $this->mapDirection($criteria->headline);
            return new Headline($direction);
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

        throw new InvalidArgumentException('Sort criteria not found');
    }

    private function mapDirection(SortDirection $direction): Direction
    {
        return $direction === SortDirection::ASC
            ? Direction::ASC
            : Direction::DESC;
    }
}
