<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ArchiveFilter;
use Atoolo\Search\Dto\Search\Query\Filter\CategoryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentSectionTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\Filter;
use Atoolo\Search\Dto\Search\Query\Filter\GroupFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
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
        if (!isset($filter->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new ObjectTypeFilter(
            $filter->key,
            ...$filter->objectTypes
        );
    }

    private function createContentSectionTypeFilter(
        InputFilter $filter
    ): ContentSectionTypeFilter {
        if (!isset($filter->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new ContentSectionTypeFilter(
            $filter->key,
            ...$filter->contentSectionTypes
        );
    }

    private function createCategoryFilter(
        InputFilter $filter
    ): CategoryFilter {
        if (!isset($filter->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new CategoryFilter(
            $filter->key,
            ...$filter->categories
        );
    }

    private function createSiteFilter(
        InputFilter $filter
    ): SiteFilter {
        if (!isset($filter->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new SiteFilter(
            $filter->key,
            ...$filter->sites
        );
    }

    private function createGroupFilter(
        InputFilter $filter
    ): GroupFilter {
        if (!isset($filter->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new GroupFilter(
            $filter->key,
            ...$filter->groups
        );
    }
}
