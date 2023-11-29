<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SelectQueryInput;
use Atoolo\GraphQL\Search\Types\QueryDefaultOperator;
use Atoolo\Search\Dto\Search\Query\SelectQuery;
use Atoolo\Search\Dto\Search\Query\SelectQueryBuilder;

class SelectQueryFactory
{
    public function create(SelectQueryInput $input): SelectQuery
    {
        $builder = SelectQuery::builder()
            ->core($input->core);

        $this->addTextFilter($builder, $input);
        $this->addPagination($builder, $input);
        $this->addFilterList($builder, $input);
        $this->addFacetList($builder, $input);

        return $builder->build();
    }

    private function addTextFilter(
        SelectQueryBuilder $builder,
        SelectQueryInput $input
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
        SelectQueryInput $input
    ): void {
        if (isset($input->offset)) {
            $builder->offset($input->offset);
        }
    }
    private function addFilterList(
        SelectQueryBuilder $builder,
        SelectQueryInput $input
    ): void {
        if (!isset($input->filterList)) {
            return;
        }
        $factory = new FilterListFactory();
        foreach ($factory->create($input->filterList) as $filter) {
            $builder->filter($filter);
        }
    }

    private function addFacetList(
        SelectQueryBuilder $builder,
        SelectQueryInput $input
    ): void {
        if (!isset($input->facetList)) {
            return;
        }
        $factory = new FacetListFactory();
        foreach ($factory->create($input->facetList) as $facet) {
            $builder->facet($facet);
        }
    }
}
