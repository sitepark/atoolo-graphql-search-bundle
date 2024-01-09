<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\Search\Dto\Search\Query\Filter\AndFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ArchiveFilter;
use Atoolo\Search\Dto\Search\Query\Filter\CategoryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentSectionTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\Filter;
use Atoolo\Search\Dto\Search\Query\Filter\GroupFilter;
use Atoolo\Search\Dto\Search\Query\Filter\NotFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\OrFilter;
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
        foreach ($inputFilterList as $inputFilter) {
            if (isset($inputFilter->archive)) {
                continue;
            }
            $filterList[] = $this->createFilter($inputFilter);
        }
        if ($this->withArchiveFilter($inputFilterList)) {
            $filterList[] = new ArchiveFilter();
        }
        return $filterList;
    }

    private function createFilter(InputFilter $filter): Filter
    {
        if (isset($filter->objectTypes)) {
            return $this->createObjectTypeFilter($filter);
        }
        if (isset($filter->contentSectionTypes)) {
            return $this->createContentSectionTypeFilter($filter);
        }
        if (isset($filter->categories)) {
            return $this->createCategoryFilter($filter);
        }
        if (isset($filter->sites)) {
            return $this->createSiteFilter($filter);
        }
        if (isset($filter->groups)) {
            return $this->createGroupFilter($filter);
        }
        if (isset($filter->and)) {
            return $this->createAndFilter($filter);
        }
        if (isset($filter->or)) {
            return $this->createOrFilter($filter);
        }
        if (isset($filter->not)) {
            return $this->createNotFilter($filter);
        }
        throw new InvalidArgumentException(
            "Unable to create filter\n" . print_r($filter, true)
        );
    }

    /**
     * Normally, the archived entries should not be part of the search result.
     * In this case, the archive filter must also be set. Archived entries
     * should only be found in exceptional cases. This must then be specified
     * explicitly. In this case, the filter is not added.
     *
     * @param InputFilter[] $inputFilterList
     */
    private function withArchiveFilter(array $inputFilterList): bool
    {
        foreach ($inputFilterList as $inputFilter) {
            if (
                isset($inputFilter->archive) &&
                $inputFilter->archive === true
            ) {
                return false;
            }
        }
        return true;
    }

    private function createObjectTypeFilter(
        InputFilter $filter
    ): ObjectTypeFilter {
        return new ObjectTypeFilter(
            $filter->key ?? null,
            ...$filter->objectTypes
        );
    }

    private function createContentSectionTypeFilter(
        InputFilter $filter
    ): ContentSectionTypeFilter {
        return new ContentSectionTypeFilter(
            $filter->key ?? null,
            ...$filter->contentSectionTypes
        );
    }

    private function createCategoryFilter(
        InputFilter $filter
    ): CategoryFilter {
        return new CategoryFilter(
            $filter->key ?? null,
            ...$filter->categories
        );
    }

    private function createSiteFilter(
        InputFilter $filter
    ): SiteFilter {
        return new SiteFilter(
            $filter->key ?? null,
            ...$filter->sites
        );
    }

    private function createGroupFilter(
        InputFilter $filter
    ): GroupFilter {
        return new GroupFilter(
            $filter->key ?? null,
            ...$filter->groups
        );
    }

    private function createAndFilter(
        InputFilter $filter
    ): AndFilter {
        $filterList = [];
        foreach ($filter->and as $filterItem) {
            $filterList[] = $this->createFilter($filterItem);
        }
        return new AndFilter(
            $filter->key ?? null,
            $filterList
        );
    }

    private function createOrFilter(
        InputFilter $filter
    ): OrFilter {
        $filterList = [];
        foreach ($filter->or as $filterItem) {
            $filterList[] = $this->createFilter($filterItem);
        }
        return new OrFilter(
            $filter->key ?? null,
            $filterList
        );
    }

    private function createNotFilter(
        InputFilter $filter
    ): NotFilter {
        return new NotFilter(
            $filter->key ?? null,
            $this->createFilter($filter->not)
        );
    }
}
