<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Types\QueryOperator;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\Boosting;
use Atoolo\Search\Dto\Search\Query\SearchQuery;
use Atoolo\Search\Dto\Search\Query\SearchQueryBuilder;

class SearchQueryFactory
{
    private readonly FilterListFactory $filterFactory;

    private readonly FacetListFactory $facetFactory;

    private readonly SortCriteriaFactory $sortCriteriaFactory;

    public function __construct()
    {
        $this->filterFactory = new FilterListFactory();
        $this->facetFactory = new FacetListFactory();
        $this->sortCriteriaFactory = new SortCriteriaFactory();
    }

    public function create(SearchInput $input): SearchQuery
    {
        $builder = new SearchQueryBuilder();
        $builder->lang(ResourceLanguage::of($input->lang));
        $builder->archive($input->archive ?? false);
        $builder->spellcheck($input->spellcheck ?? false);

        $this->addTextFilter($builder, $input);
        $this->addSort($builder, $input);
        $this->addPagination($builder, $input);
        $this->addFilterList($builder, $input);
        $this->addFacetList($builder, $input);
        $this->addDateTimeZone($builder, $input);
        $this->addBoosting($builder, $input);
        $this->addDistanceReferencePoint($builder, $input);
        $builder->explain($input->explain);

        return $builder->build();
    }

    private function addTextFilter(
        SearchQueryBuilder $builder,
        SearchInput $input,
    ): void {
        $builder->defaultQueryOperator(
            $input->defaultQueryOperator === QueryOperator::AND
                ? \Atoolo\Search\Dto\Search\Query\QueryOperator::AND
                : \Atoolo\Search\Dto\Search\Query\QueryOperator::OR,
        );

        if (isset($input->text)) {
            $builder->text($input->text);
        }
    }

    private function addSort(
        SearchQueryBuilder $builder,
        SearchInput $input,
    ): void {
        if (!isset($input->sort)) {
            return;
        }
        foreach ($input->sort as $criteria) {
            $builder->sort($this->sortCriteriaFactory->create($criteria));
        }
    }

    private function addPagination(
        SearchQueryBuilder $builder,
        SearchInput $input,
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
        SearchInput $input,
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
        SearchInput $input,
    ): void {
        if (!isset($input->facets)) {
            return;
        }
        foreach ($this->facetFactory->create($input->facets) as $facet) {
            $builder->facet($facet);
        }
    }

    private function addDateTimeZone(
        SearchQueryBuilder $builder,
        SearchInput $input,
    ): void {
        if (!isset($input->timeZone)) {
            return;
        }
        $builder->timeZone($input->timeZone);
    }

    private function addBoosting(
        SearchQueryBuilder $builder,
        SearchInput $input,
    ): void {
        if (!isset($input->boosting)) {
            return;
        }
        $boosting = new Boosting(
            queryFields: $input->boosting->queryFields ?? [],
            phraseFields: $input->boosting->phraseFields ?? [],
            boostQueries: $input->boosting->boostQueries ?? [],
            boostFunctions: $input->boosting->boostFunctions ?? [],
            tie: $input->boosting->tie ?? 0.0,
        );
        $builder->boosting($boosting);
    }

    private function addDistanceReferencePoint(
        SearchQueryBuilder $builder,
        SearchInput $input,
    ): void {
        if (!isset($input->distanceReferencePoint)) {
            return;
        }
        $builder->distanceReferencePoint($input->distanceReferencePoint->toGeoPoint());
    }
}
