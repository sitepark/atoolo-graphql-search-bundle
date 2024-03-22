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
        return $this->tryCreateObjectTypeFacet($facet)
            ?? $this->tryCreateContentSectionTypeFacet($facet)
            ?? $this->tryCreateCategoryFacet($facet)
            ?? $this->tryCreateSiteFacet($facet)
            ?? $this->tryCreateGroupFacet($facet)
            ?? (throw new InvalidArgumentException(
                "Unable to create facet\n" . print_r($facet, true)
            ));
    }

    private function tryCreateObjectTypeFacet(
        InputFacet $facet
    ): ?ObjectTypeFacet {
        return !empty($facet->objectTypes)
            ? new ObjectTypeFacet(
                $facet->key,
                $facet->objectTypes,
                $facet->excludeFilter
            )
            : null;
    }

    private function tryCreateContentSectionTypeFacet(
        InputFacet $facet
    ): ?ContentSectionTypeFacet {
        return !empty($facet->contentSectionTypes)
            ? new ContentSectionTypeFacet(
                $facet->key,
                $facet->contentSectionTypes,
                $facet->excludeFilter
            )
            : null;
    }

    private function tryCreateCategoryFacet(
        InputFacet $facet
    ): ?CategoryFacet {
        return !empty($facet->categories)
            ? new CategoryFacet(
                $facet->key,
                $facet->categories,
                $facet->excludeFilter
            )
            : null;
    }

    private function tryCreateSiteFacet(
        InputFacet $facet
    ): ?SiteFacet {
        return !empty($facet->sites)
            ? new SiteFacet(
                $facet->key,
                $facet->sites,
                $facet->excludeFilter
            )
            : null;
    }

    private function tryCreateGroupFacet(
        InputFacet $facet
    ): ?GroupFacet {
        return !empty($facet->groups)
            ? new GroupFacet(
                $facet->key,
                $facet->groups,
                $facet->excludeFilter
            )
            : null;
    }
}
