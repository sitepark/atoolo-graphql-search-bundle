<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\Search\Dto\Search\Query\DateRangeRound;
use Atoolo\Search\Dto\Search\Query\Filter\AbsoluteDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\AndFilter;
use Atoolo\Search\Dto\Search\Query\Filter\CategoryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentSectionTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\Filter;
use Atoolo\Search\Dto\Search\Query\Filter\GeoLocatedFilter;
use Atoolo\Search\Dto\Search\Query\Filter\GroupFilter;
use Atoolo\Search\Dto\Search\Query\Filter\IdFilter;
use Atoolo\Search\Dto\Search\Query\Filter\NotFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\OrFilter;
use Atoolo\Search\Dto\Search\Query\Filter\QueryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\RelativeDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SiteFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SpatialArbitraryRectangleFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SpatialOrbitalFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SpatialOrbitalMode;
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
            $filterList[] = $this->createFilter($inputFilter);
        }
        return $filterList;
    }

    private function createFilter(InputFilter $filter): Filter
    {
        return $this->tryCreateObjectTypeFilter($filter)
            ?? $this->tryCreateContentSectionTypeFilter($filter)
            ?? $this->tryCreateCategoryFilter($filter)
            ?? $this->tryCreateSiteFilter($filter)
            ?? $this->tryCreateGroupFilter($filter)
            ?? $this->tryCreateIdFilter($filter)
            ?? $this->tryCreateAbsoluteDateRangeFilter($filter)
            ?? $this->tryCreateRelativeDateRangeFilter($filter)
            ?? $this->tryCreateAndFilter($filter)
            ?? $this->tryCreateOrFilter($filter)
            ?? $this->tryCreateNotFilter($filter)
            ?? $this->tryCreateQueryFilter($filter)
            ?? $this->tryGeoLocatedFilter($filter)
            ?? $this->tryCreateSpatialOrbitalFilter($filter)
            ?? $this->tryCreateSpatialArbitraryRectangleFilter($filter)
            ?? (throw new InvalidArgumentException(
                "Unable to create filter\n" . print_r($filter, true),
            ));
    }

    private function tryCreateObjectTypeFilter(
        InputFilter $filter,
    ): ?ObjectTypeFilter {
        return !empty($filter->objectTypes)
            ? new ObjectTypeFilter($filter->objectTypes, $filter->key)
            : null;
    }

    private function tryCreateContentSectionTypeFilter(
        InputFilter $filter,
    ): ?ContentSectionTypeFilter {
        return !empty($filter->contentSectionTypes)
            ? new ContentSectionTypeFilter(
                $filter->contentSectionTypes,
                $filter->key,
            )
            : null;
    }

    private function tryCreateCategoryFilter(
        InputFilter $filter,
    ): ?CategoryFilter {
        return !empty($filter->categories)
            ? new CategoryFilter($filter->categories, $filter->key)
            : null;
    }

    private function tryCreateSiteFilter(
        InputFilter $filter,
    ): ?SiteFilter {
        return !empty($filter->sites)
            ? new SiteFilter($filter->sites, $filter->key)
            : null;
    }

    private function tryCreateGroupFilter(
        InputFilter $filter,
    ): ?GroupFilter {
        return !empty($filter->groups)
            ? new GroupFilter($filter->groups, $filter->key)
            : null;
    }

    private function tryCreateIdFilter(
        InputFilter $filter,
    ): ?IdFilter {
        return !empty($filter->ids)
            ? new IdFilter($filter->ids, $filter->key)
            : null;
    }

    private function tryCreateAbsoluteDateRangeFilter(
        InputFilter $filter,
    ): ?AbsoluteDateRangeFilter {
        return ($filter->absoluteDateRange !== null)
            ? new AbsoluteDateRangeFilter(
                $filter->absoluteDateRange->from,
                $filter->absoluteDateRange->to,
                $filter->key,
            )
            : null;
    }

    private function tryCreateRelativeDateRangeFilter(
        InputFilter $filter,
    ): ?RelativeDateRangeFilter {
        return ($filter->relativeDateRange !== null)
            ? new RelativeDateRangeFilter(
                $filter->relativeDateRange->base,
                $filter->relativeDateRange->before,
                $filter->relativeDateRange->after,
                $this->mapDateRangeRound(
                    $filter->relativeDateRange->roundStart,
                ),
                $this->mapDateRangeRound(
                    $filter->relativeDateRange->roundEnd,
                ),
                $filter->key,
            )
            : null;
    }

    private function tryGeoLocatedFilter(
        InputFilter $filter,
    ): ?GeoLocatedFilter {

        if ($filter->geoLocated === null) {
            return null;
        }
        return new GeoLocatedFilter(
            exists: $filter->geoLocated,
            key: $filter->key,
        );
    }

    private function tryCreateSpatialOrbitalFilter(
        InputFilter $filter,
    ): ?SpatialOrbitalFilter {
        if ($filter->spatialOrbital === null) {
            return null;
        }

        if ($filter->spatialOrbital->distance === null) {
            throw new InvalidArgumentException(
                'Distance is required for spatial orbital filter',
            );
        }

        if ($filter->spatialOrbital->centerPoint === null) {
            throw new InvalidArgumentException(
                'Center point is required for spatial orbital filter',
            );
        }

        return new SpatialOrbitalFilter(
            distance: $filter->spatialOrbital->distance,
            centerPoint: $filter->spatialOrbital->centerPoint->toGeoPoint(),
            mode: $filter->spatialOrbital->mode
                ? SpatialOrbitalMode::from($filter->spatialOrbital->mode->value)
                : SpatialOrbitalMode::GREAT_CIRCLE_DISTANCE,
            key: $filter->key,
        );
    }

    private function tryCreateSpatialArbitraryRectangleFilter(
        InputFilter $filter,
    ): ?SpatialArbitraryRectangleFilter {
        if ($filter->spatialArbitraryRectangle === null) {
            return null;
        }

        if ($filter->spatialArbitraryRectangle->lowerLeftCorner === null) {
            throw new InvalidArgumentException(
                'LowerLeftCorner is required for arbitrary rectangle filter',
            );
        }

        if ($filter->spatialArbitraryRectangle->upperRightCorner === null) {
            throw new InvalidArgumentException(
                'UpperRightCorner is required for arbitrary rectangle filter',
            );
        }

        return new SpatialArbitraryRectangleFilter(
            lowerLeftCorner: $filter->spatialArbitraryRectangle->lowerLeftCorner->toGeoPoint(),
            upperRightCorner: $filter->spatialArbitraryRectangle->upperRightCorner->toGeoPoint(),
            key: $filter->key,
        );
    }

    private function tryCreateAndFilter(
        InputFilter $filter,
    ): ?AndFilter {
        return !empty($filter->and)
            ? new AndFilter(
                array_map(fn($and) => $this->createFilter($and), $filter->and),
                $filter->key,
            )
            : null;
    }

    private function tryCreateOrFilter(
        InputFilter $filter,
    ): ?OrFilter {
        return !empty($filter->or)
            ? new OrFilter(
                array_map(fn($or) => $this->createFilter($or), $filter->or),
                $filter->key,
            )
            : null;
    }

    private function tryCreateNotFilter(
        InputFilter $filter,
    ): ?NotFilter {
        return isset($filter->not)
            ? new NotFilter($this->createFilter($filter->not), $filter->key)
            : null;
    }

    private function tryCreateQueryFilter(
        InputFilter $filter,
    ): ?QueryFilter {
        return isset($filter->query)
            ? new QueryFilter($filter->query, $filter->key)
            : null;
    }

    private function mapDateRangeRound(
        ?\Atoolo\GraphQL\Search\Types\DateRangeRound $round,
    ): ?DateRangeRound {
        return $round !== null
            ? DateRangeRound::from($round->name)
            : null;
    }
}
