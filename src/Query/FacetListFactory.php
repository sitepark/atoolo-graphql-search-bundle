<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\Search\Dto\Search\Query\Facet\CategoryFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ContentSectionTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\Facet;
use Atoolo\Search\Dto\Search\Query\Facet\GroupFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ObjectTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\SiteFacet;
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
        if (!empty($facet->objectTypes)) {
            return $this->createObjectTypeFacet($facet);
        }
        if (!empty($facet->contentSectionTypes)) {
            return $this->createContentSectionTypeFacet($facet);
        }
        if (!empty($facet->categories)) {
            return $this->createCategoryFacet($facet);
        }
        if (!empty($facet->sites)) {
            return $this->createSiteFacet($facet);
        }
        if (!empty($facet->groups)) {
            return $this->createGroupFacet($facet);
        }
        throw new InvalidArgumentException(
            "Unable to create facet\n" . print_r($facet, true)
        );
    }

    private function createObjectTypeFacet(
        InputFacet $facet
    ): ObjectTypeFacet {
        return new ObjectTypeFacet(
            $facet->key,
            $facet->objectTypes ?? [],
            $facet->excludeFilter
        );
    }

    private function createContentSectionTypeFacet(
        InputFacet $facet
    ): ContentSectionTypeFacet {
        return new ContentSectionTypeFacet(
            $facet->key,
            $facet->contentSectionTypes,
            $facet->excludeFilter
        );
    }

    private function createCategoryFacet(
        InputFacet $facet
    ): CategoryFacet {
        return new CategoryFacet(
            $facet->key,
            $facet->categories,
            $facet->excludeFilter
        );
    }

    private function createSiteFacet(
        InputFacet $facet
    ): SiteFacet {
        return new SiteFacet(
            $facet->key,
            $facet->sites,
            $facet->excludeFilter
        );
    }

    private function createGroupFacet(
        InputFacet $facet
    ): GroupFacet {
        return new GroupFacet(
            $facet->key,
            $facet->groups,
            $facet->excludeFilter
        );
    }
}
