<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Types\QueryDefaultOperator;
use Atoolo\Search\Dto\Search\Query\SelectQuery;
use Atoolo\Search\Dto\Search\Query\SelectQueryBuilder;

class SuggestQueryFactory
{
    public function create(SearchInput $input): SelectQuery
    {
        $builder = SelectQuery::builder()
            ->index($input->index);

        if (isset($input->limit)) {
            $builder->limit($input->limit);
        }
        if (isset($input->offset)) {
            $builder->offset($input->offset);
        }

        $this->addTextFilter($builder, $input);
        $this->addPagination($builder, $input);
        $this->addFilterList($builder, $input);
        $this->addFacetList($builder, $input);

        return $builder->build();
    }

    private function addTextFilter(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
        if (
            isset($input->queryDefaultOperator) &&
            $input->queryDefaultOperator === QueryDefaultOperator::OR
        ) {
            $builder->queryDefaultOperator(
                \Atoolo\Search\Dto\Search\Query\QueryDefaultOperator::OR
            );
        } else {
            $builder->queryDefaultOperator(
                \Atoolo\Search\Dto\Search\Query\QueryDefaultOperator::AND
            );
        }
        if (isset($input->text)) {
            $builder->text($input->text);
        }
    }

    private function addPagination(
        SelectQueryBuilder $builder,
        SearchInput $input
    ): void {
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
