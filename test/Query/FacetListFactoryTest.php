<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\GraphQL\Search\Query\FacetListFactory;
use Atoolo\Search\Dto\Search\Query\Facet\CategoryFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ContentSectionTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\GroupFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ObjectTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\SiteFacet;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FacetListFactory::class)]
class FacetListFactoryTest extends TestCase
{
    public function testCreateObjectTypeFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'objectType';
        $facet->objectTypes = ['content'];

        $factory = new FacetListFactory();
        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [new ObjectTypeFacet('objectType', ['content'])],
            $facetList,
            'objectType facet expected'
        );
    }

    public function testCreateContentSectionTypeFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'contentSectionType';
        $facet->contentSectionTypes = ['youtube'];

        $factory = new FacetListFactory();
        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [new ContentSectionTypeFacet('contentSectionType', ['youtube'])],
            $facetList,
            'contentSectionType facet expected'
        );
    }

    public function testCreateCategoriesFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'categories';
        $facet->categories = ['123'];

        $factory = new FacetListFactory();
        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [new CategoryFacet('categories', ['123'])],
            $facetList,
            'categories facet expected'
        );
    }

    public function testCreateSiteFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'site';
        $facet->sites = ['123'];

        $factory = new FacetListFactory();
        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [new SiteFacet('site', ['123'])],
            $facetList,
            'site facet expected'
        );
    }

    public function testCreateGroupFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'group';
        $facet->groups = ['123'];

        $factory = new FacetListFactory();
        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [new GroupFacet('group', ['123'])],
            $facetList,
            'group facet expected'
        );
    }

    public function testWithInvalidInputFacet(): void
    {
        $input = new InputFacet();
        $input->key = 'objectType';

        $factory = new FacetListFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create([$input]);
    }
}
