<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\Search\Dto\Search\Query\Filter\AbsoluteDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\AndFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ArchiveFilter;
use Atoolo\Search\Dto\Search\Query\Filter\CategoryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentSectionTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\Filter;
use Atoolo\Search\Dto\Search\Query\Filter\GroupFilter;
use Atoolo\Search\Dto\Search\Query\Filter\NotFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\OrFilter;
use Atoolo\Search\Dto\Search\Query\Filter\QueryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SiteFilter;
use InvalidArgumentException;

class FilterListFactory
{
    /**
     * @param InputFilter[] $inputFilterList
     * @return Filter[]
     */
    public function create(array $inputFilterList): array
    {
        $filterList = [];
        $includeArchived = false;
        foreach ($inputFilterList as $inputFilter) {
            if ($inputFilter->archive !== null) {
                $includeArchived = $includeArchived || $inputFilter->archive;
                continue;
            }
            $filterList[] = $this->createFilter($inputFilter);
        }

        if (!$includeArchived) {
            $filterList[] = new ArchiveFilter();
        }

        return $filterList;
    }

    private function createFilter(InputFilter $filter): Filter
    {
        return $this->tryCreateArchiveFilter($filter)
            ?? $this->tryCreateObjectTypeFilter($filter)
            ?? $this->tryCreateContentSectionTypeFilter($filter)
            ?? $this->tryCreateCategoryFilter($filter)
            ?? $this->tryCreateSiteFilter($filter)
            ?? $this->tryCreateGroupFilter($filter)
            ?? $this->tryCreateAbsoluteDateRangeFilter($filter)
            ?? $this->tryCreateAndFilter($filter)
            ?? $this->tryCreateOrFilter($filter)
            ?? $this->tryCreateNotFilter($filter)
            ?? $this->tryCreateQueryFilter($filter)
            ?? (throw new InvalidArgumentException(
                "Unable to create filter\n" . print_r($filter, true)
            ));
    }

    private function tryCreateArchiveFilter(
        InputFilter $filter
    ): ?ArchiveFilter {
        return $filter->archive ? new ArchiveFilter() : null;
    }

    private function tryCreateObjectTypeFilter(
        InputFilter $filter
    ): ?ObjectTypeFilter {
        return !empty($filter->objectTypes)
            ? new ObjectTypeFilter($filter->objectTypes, $filter->key)
            : null;
    }

    private function tryCreateContentSectionTypeFilter(
        InputFilter $filter
    ): ?ContentSectionTypeFilter {
        return !empty($filter->contentSectionTypes)
            ? new ContentSectionTypeFilter(
                $filter->contentSectionTypes,
                $filter->key
            )
            : null;
    }

    private function tryCreateCategoryFilter(
        InputFilter $filter
    ): ?CategoryFilter {
        return !empty($filter->categories)
            ? new CategoryFilter($filter->categories, $filter->key)
            : null;
    }

    private function tryCreateSiteFilter(
        InputFilter $filter
    ): ?SiteFilter {
        return !empty($filter->sites)
            ? new SiteFilter($filter->sites, $filter->key)
            : null;
    }

    private function tryCreateGroupFilter(
        InputFilter $filter
    ): ?GroupFilter {
        return !empty($filter->groups)
            ? new GroupFilter($filter->groups, $filter->key)
            : null;
    }

    private function tryCreateAbsoluteDateRangeFilter(
        InputFilter $filter
    ): ?AbsoluteDateRangeFilter {
        return ($filter->absoluteDateRange != null)
            ? new AbsoluteDateRangeFilter(
                $filter->absoluteDateRange->from,
                $filter->absoluteDateRange->to,
                $filter->key
            )
            : null;
    }

    private function tryCreateAndFilter(
        InputFilter $filter
    ): ?AndFilter {
        return !empty($filter->and)
            ? new AndFilter(
                array_map(fn($and) => $this->createFilter($and), $filter->and),
                $filter->key
            )
            : null;
    }

    private function tryCreateOrFilter(
        InputFilter $filter
    ): ?OrFilter {
        return !empty($filter->or)
            ? new OrFilter(
                array_map(fn($or) => $this->createFilter($or), $filter->or),
                $filter->key
            )
            : null;
    }

    private function tryCreateNotFilter(
        InputFilter $filter
    ): ?NotFilter {
        return isset($filter->not)
            ? new NotFilter($this->createFilter($filter->not), $filter->key)
            : null;
    }

    private function tryCreateQueryFilter(
        InputFilter $filter
    ): ?QueryFilter {
        return isset($filter->query)
            ? new QueryFilter($filter->query, $filter->key)
            : null;
    }
}
