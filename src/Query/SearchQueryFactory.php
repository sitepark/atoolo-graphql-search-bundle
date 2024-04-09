<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Types\QueryOperator;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\SearchQuery;
use Atoolo\Search\Dto\Search\Query\SearchQueryBuilder;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Headline;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use InvalidArgumentException;

class SearchQueryFactory
{
    private readonly FilterListFactory $filterFactory;

    private readonly FacetListFactory $facetFactory;

    public function __construct()
    {
        $this->filterFactory = new FilterListFactory();
        $this->facetFactory = new FacetListFactory();
    }

    public function create(SearchInput $input): SearchQuery
    {
        $builder = new SearchQueryBuilder();
        $builder->lang($input->lang ?? '');

        $this->addTextFilter($builder, $input);
        $this->addSort($builder, $input);
        $this->addPagination($builder, $input);
        $this->addFilterList($builder, $input);
        $this->addFacetList($builder, $input);

        return $builder->build();
    }

    private function addTextFilter(
        SearchQueryBuilder $builder,
        SearchInput $input
    ): void {
        $builder->defaultQueryOperator(
            $input->defaultQueryOperator === QueryOperator::OR
                ? \Atoolo\Search\Dto\Search\Query\QueryOperator::OR
                : \Atoolo\Search\Dto\Search\Query\QueryOperator::AND
        );

        if (isset($input->text)) {
            $builder->text($input->text);
        }
    }

    private function addSort(
        SearchQueryBuilder $builder,
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
        SearchQueryBuilder $builder,
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
        SearchQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (!isset($input->filter)) {
            return;
        }
        foreach ($this->filterFactory->create($input->filter) as $filter) {
            $builder->filter($filter);
        }
    }

    private function addFacetList(
        SearchQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (!isset($input->facets)) {
            return;
        }
        foreach ($this->facetFactory->create($input->facets) as $facet) {
            $builder->facet($facet);
        }
    }
}