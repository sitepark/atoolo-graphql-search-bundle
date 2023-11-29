<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ContentSectionTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\CategoryFacet;
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
        if (isset($facet->objectTypes)) {
            return $this->createObjectTypeFacet($facet);
        }
        if (isset($facet->contentSectionTypes)) {
            return $this->createContentSectionTypeFacet($facet);
        }
        if (isset($facet->categories)) {
            return $this->createCategoryFacet($facet);
        }
        if (isset($facet->site)) {
            return $this->createSiteFacet($facet);
        }
        if (isset($facet->group)) {
            return $this->createGroupFacet($facet);
        }
        throw new InvalidArgumentException(
            "Unable to create facet\n" . print_r($facet, true)
        );
    }

    private function createObjectTypeFacet(
        InputFacet $facet
    ): ObjectTypeFacet {
        if (!isset($facet->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new ObjectTypeFacet(
            $facet->key,
            ...$facet->objectTypes
        );
    }

    private function createContentSectionTypeFacet(
        InputFacet $facet
    ): ContentSectionTypeFacet {
        if (!isset($facet->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new ContentSectionTypeFacet(
            $facet->key,
            ...$facet->contentSectionTypes
        );
    }

    private function createCategoryFacet(
        InputFacet $facet
    ): CategoryFacet {
        if (!isset($facet->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new CategoryFacet(
            $facet->key,
            ...$facet->categories
        );
    }

    private function createSiteFacet(
        InputFacet $facet
    ): SiteFacet {
        if (!isset($facet->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new SiteFacet(
            $facet->key,
            ...$facet->site
        );
    }

    private function createGroupFacet(
        InputFacet $facet
    ): GroupFacet {
        if (!isset($facet->key)) {
            throw new InvalidArgumentException('key missing');
        }
        return new GroupFacet(
            $facet->key,
            ...$facet->group
        );
    }
}
