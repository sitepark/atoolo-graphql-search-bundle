<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\Search\Dto\Search\Query\DateRangeRound;
use Atoolo\Search\Dto\Search\Query\Facet\AbsoluteDateRangeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\CategoryFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ContentSectionTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ContentTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\Facet;
use Atoolo\Search\Dto\Search\Query\Facet\GroupFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ObjectTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\QueryFacet;
use Atoolo\Search\Dto\Search\Query\Facet\QueryTemplateFacet;
use Atoolo\Search\Dto\Search\Query\Facet\RelativeDateRangeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\SiteFacet;
use Atoolo\Search\Dto\Search\Query\Facet\SourceFacet;
use Atoolo\Search\Dto\Search\Query\Facet\SpatialDistanceRangeFacet;
use InvalidArgumentException;

class FacetListFactory
{
    /**
     * @param InputFacet[] $inputFacetList
     * @return Facet[]
     */
    public function create(array $inputFacetList): array
    {
        $facetList = [];
        foreach ($inputFacetList as $inputFacet) {
            $facetList[] = $this->createFacet($inputFacet);
        }
        return $facetList;
    }

    private function createFacet(InputFacet $facet): Facet
    {
        return $this->tryCreateObjectTypeFacet($facet)
            ?? $this->tryCreateContentSectionTypeFacet($facet)
            ?? $this->tryCreateCategoryFacet($facet)
            ?? $this->tryCreateSiteFacet($facet)
            ?? $this->tryCreateSourceFacet($facet)
            ?? $this->tryCreateContentTypeFacet($facet)
            ?? $this->tryCreateGroupFacet($facet)
            ?? $this->tryCreateAbsoluteDateRangeFacet($facet)
            ?? $this->tryCreateRelativeDateRangeFacet($facet)
            ?? $this->tryCreateGeoDistanceRangeFacet($facet)
            ?? $this->tryCreateQueryFacet($facet)
            ?? $this->tryCreateQueryTemplateFacet($facet)
            ?? (throw new InvalidArgumentException(
                "Unable to create facet\n" . print_r($facet, true),
            ));
    }

    private function tryCreateObjectTypeFacet(
        InputFacet $facet,
    ): ?ObjectTypeFacet {
        return !empty($facet->objectTypes)
            ? new ObjectTypeFacet(
                $facet->key,
                $facet->objectTypes,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateContentSectionTypeFacet(
        InputFacet $facet,
    ): ?ContentSectionTypeFacet {
        return !empty($facet->contentSectionTypes)
            ? new ContentSectionTypeFacet(
                $facet->key,
                $facet->contentSectionTypes,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateCategoryFacet(
        InputFacet $facet,
    ): ?CategoryFacet {
        return !empty($facet->categories)
            ? new CategoryFacet(
                $facet->key,
                $facet->categories,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateSiteFacet(
        InputFacet $facet,
    ): ?SiteFacet {
        return !empty($facet->sites)
            ? new SiteFacet(
                $facet->key,
                $facet->sites,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateSourceFacet(
        InputFacet $facet,
    ): ?SourceFacet {
        return !empty($facet->sources)
            ? new SourceFacet(
                $facet->key,
                $facet->sources,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateContentTypeFacet(
        InputFacet $facet,
    ): ?ContentTypeFacet {
        return !empty($facet->contentTypes)
            ? new ContentTypeFacet(
                $facet->key,
                $facet->contentTypes,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateGroupFacet(
        InputFacet $facet,
    ): ?GroupFacet {
        return !empty($facet->groups)
            ? new GroupFacet(
                $facet->key,
                $facet->groups,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateAbsoluteDateRangeFacet(
        InputFacet $facet,
    ): ?AbsoluteDateRangeFacet {
        if (empty($facet->absoluteDateRange)) {
            return null;
        }
        if (
            $facet->absoluteDateRange->from === null &&
            $facet->absoluteDateRange->to === null
        ) {
            throw new InvalidArgumentException(
                'At least `from` or `to` must be specified for ' .
                'the `absoluteDateRange`',
            );
        }
        return new AbsoluteDateRangeFacet(
            $facet->key,
            $facet->absoluteDateRange->from,
            $facet->absoluteDateRange->to,
            $facet->absoluteDateRange->gap,
            $facet->excludeFilter ?? [],
        );
    }

    private function tryCreateRelativeDateRangeFacet(
        InputFacet $facet,
    ): ?RelativeDateRangeFacet {

        return !empty($facet->relativeDateRange)
            ? new RelativeDateRangeFacet(
                $facet->key,
                $facet->relativeDateRange->base,
                $facet->relativeDateRange->before,
                $facet->relativeDateRange->after,
                $facet->relativeDateRange->gap,
                $this->mapDateRangeRound($facet->relativeDateRange->roundStart),
                $this->mapDateRangeRound($facet->relativeDateRange->roundEnd),
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateGeoDistanceRangeFacet(
        InputFacet $facet,
    ): ?SpatialDistanceRangeFacet {

        if ($facet->spatialDistanceRange === null) {
            return null;
        }

        if ($facet->spatialDistanceRange->point === null) {
            throw new InvalidArgumentException(
                'Point is required for geo distance range facet',
            );
        }

        return new SpatialDistanceRangeFacet(
            $facet->key,
            $facet->spatialDistanceRange->point->toGeoPoint(),
            $facet->spatialDistanceRange->from,
            $facet->spatialDistanceRange->to,
            $facet->excludeFilter ?? [],
        );
    }

    private function tryCreateQueryFacet(
        InputFacet $facet,
    ): ?QueryFacet {
        return !empty($facet->query)
            ? new QueryFacet(
                $facet->key,
                $facet->query,
                $facet->excludeFilter ?? [],
            )
            : null;
    }

    private function tryCreateQueryTemplateFacet(
        InputFacet $facet,
    ): ?QueryTemplateFacet {
        return !empty($facet->queryTemplate)
            ? new QueryTemplateFacet(
                $facet->key,
                $facet->queryTemplate->query ?? '',
                $facet->queryTemplate->variables ?? [],
                $facet->excludeFilter ?? [],
            )
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
