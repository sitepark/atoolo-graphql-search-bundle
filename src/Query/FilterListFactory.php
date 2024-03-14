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
use Atoolo\Search\Dto\Search\Query\Filter\QueryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SiteFilter;
use InvalidArgumentException;
use LogicException;

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
        if (!empty($filter->objectTypes)) {
            return $this->createObjectTypeFilter($filter);
        }
        if (!empty($filter->contentSectionTypes)) {
            return $this->createContentSectionTypeFilter($filter);
        }
        if (!empty($filter->categories)) {
            return $this->createCategoryFilter($filter);
        }
        if (!empty($filter->sites)) {
            return $this->createSiteFilter($filter);
        }
        if (!empty($filter->groups)) {
            return $this->createGroupFilter($filter);
        }
        if (!empty($filter->and)) {
            return $this->createAndFilter($filter);
        }
        if (!empty($filter->or)) {
            return $this->createOrFilter($filter);
        }
        if (isset($filter->not)) {
            return $this->createNotFilter($filter);
        }
        if (isset($filter->query)) {
            return $this->createQueryFilter($filter);
        }
        throw new InvalidArgumentException(
            "Unable to create filter\n" . print_r($filter, true)
        );
    }

    private function createObjectTypeFilter(
        InputFilter $filter
    ): ObjectTypeFilter {
        return new ObjectTypeFilter(
            $filter->objectTypes,
            $filter->key
        );
    }

    private function createContentSectionTypeFilter(
        InputFilter $filter
    ): ContentSectionTypeFilter {
        return new ContentSectionTypeFilter(
            $filter->contentSectionTypes,
            $filter->key
        );
    }

    private function createCategoryFilter(
        InputFilter $filter
    ): CategoryFilter {
        return new CategoryFilter(
            $filter->categories,
            $filter->key
        );
    }

    private function createSiteFilter(
        InputFilter $filter
    ): SiteFilter {
        return new SiteFilter(
            $filter->sites,
            $filter->key
        );
    }

    private function createGroupFilter(
        InputFilter $filter
    ): GroupFilter {
        return new GroupFilter(
            $filter->groups,
            $filter->key
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
            $filterList,
            $filter->key
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
            $filterList,
            $filter->key
        );
    }

    private function createNotFilter(
        InputFilter $filter
    ): NotFilter {
        // @codeCoverageIgnoreStart
        if ($filter->not === null) {
            throw new LogicException('not-field missing');
        }
        // @codeCoverageIgnoreEnd
        return new NotFilter(
            $this->createFilter($filter->not),
            $filter->key
        );
    }

    private function createQueryFilter(
        InputFilter $filter
    ): QueryFilter {
        // @codeCoverageIgnoreStart
        if ($filter->query === null) {
            throw new LogicException('query-field missing');
        }
        // @codeCoverageIgnoreEnd
        return new QueryFilter(
            $filter->query,
            $filter->key
        );
    }
}
