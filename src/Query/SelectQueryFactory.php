<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Types\DefaultQueryOperator;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\SelectQuery;
use Atoolo\Search\Dto\Search\Query\SelectQueryBuilder;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Headline;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use InvalidArgumentException;

class SelectQueryFactory
{
    public function create(SearchInput $input): SelectQuery
    {
        $builder = new SelectQueryBuilder();
        $builder->index($input->index);

        $this->addTextFilter($builder, $input);
        $this->addSort($builder, $input);
        $this->addPagination($builder, $input);
        $this->addFilterList($builder, $input);
        $this->addFacetList($builder, $input);

        return $builder->build();
    }

    private function addTextFilter(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
        $builder->defaultQueryOperator(
            $input->defaultQueryOperator === DefaultQueryOperator::OR
                ? \Atoolo\Search\Dto\Search\Query\DefaultQueryOperator::OR
                : \Atoolo\Search\Dto\Search\Query\DefaultQueryOperator::AND
        );

        if (isset($input->text)) {
            $builder->text($input->text);
        }
    }

    private function addSort(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (!isset($input->sort)) {
            return;
        }
        foreach ($input->sort as $criteria) {
            if (isset($criteria->name)) {
                $direction = $this->mapDirection($criteria->name);
                $builder->sort(
                    new Name($direction)
                );
            } elseif (isset($criteria->headline)) {
                $direction = $this->mapDirection($criteria->headline);
                $builder->sort(
                    new Headline($direction)
                );
            } elseif (isset($criteria->date)) {
                $direction = $this->mapDirection($criteria->date);
                $builder->sort(
                    new Date($direction)
                );
            } elseif (isset($criteria->natural)) {
                $direction = $this->mapDirection($criteria->natural);
                $builder->sort(
                    new Natural($direction)
                );
            } elseif (isset($criteria->score)) {
                $direction = $this->mapDirection($criteria->score);
                $builder->sort(
                    new Score($direction)
                );
            } else {
                throw new InvalidArgumentException('Sort criteria not found');
            }
        }
    }

    private function mapDirection(SortDirection $direction): Direction
    {
        return $direction === SortDirection::ASC
            ? Direction::ASC
            : Direction::DESC;
    }

    private function addPagination(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (isset($input->limit)) {
            $builder->limit($input->limit);
        }
        if (isset($input->offset)) {
            $builder->offset($input->offset);
        }
    }

    private function addFilterList(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (!isset($input->filter)) {
            return;
        }
        $factory = new FilterListFactory();
        foreach ($factory->create($input->filter) as $filter) {
            $builder->filter($filter);
        }
    }

    private function addFacetList(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (!isset($input->facets)) {
            return;
        }
        $factory = new FacetListFactory();
        foreach ($factory->create($input->facets) as $facet) {
            $builder->facet($facet);
        }
    }
}
