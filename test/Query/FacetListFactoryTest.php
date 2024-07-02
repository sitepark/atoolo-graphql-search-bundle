<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\AbsoluteDateRangeInputFacet;
use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\GraphQL\Search\Input\RelativeDateRangeInputFacet;
use Atoolo\GraphQL\Search\Query\FacetListFactory;
use Atoolo\GraphQL\Search\Types\DateRangeRound;
use Atoolo\Search\Dto\Search\Query\Facet\AbsoluteDateRangeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\CategoryFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ContentSectionTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\GroupFacet;
use Atoolo\Search\Dto\Search\Query\Facet\ObjectTypeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\RelativeDateRangeFacet;
use Atoolo\Search\Dto\Search\Query\Facet\SiteFacet;
use DateInterval;
use DateTime;
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
            'objectType facet expected',
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
            'contentSectionType facet expected',
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
            'categories facet expected',
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
            'site facet expected',
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
            'group facet expected',
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

    public function testWithAbsoluteDateRangeFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'key';
        $facet->absoluteDateRange = new AbsoluteDateRangeInputFacet();
        $facet->absoluteDateRange->from =
            new DateTime('2021-01-01T00:00:00+00:00');
        $facet->absoluteDateRange->to =
            new DateTime('2021-01-02T00:00:00+00:00');
        $facet->absoluteDateRange->gap =
            new DateInterval('P1D');
        $facet->excludeFilter = ['exclude'];

        $factory = new FacetListFactory();

        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [
                new AbsoluteDateRangeFacet(
                    'key',
                    new DateTime('2021-01-01T00:00:00+00:00'),
                    new DateTime('2021-01-02T00:00:00+00:00'),
                    new DateInterval('P1D'),
                    ['exclude'],
                ),
            ],
            $facetList,
            'absolute date range facet expected',
        );
    }

    public function testWithAbsoluteDateRangeFacetWithoutFromAndTo(): void
    {
        $facet = new InputFacet();
        $facet->key = 'key';
        $facet->absoluteDateRange = new AbsoluteDateRangeInputFacet();

        $factory = new FacetListFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create([$facet]);
    }

    public function testWithRelativeDateRangeFacet(): void
    {
        $facet = new InputFacet();
        $facet->key = 'key';
        $facet->relativeDateRange = new RelativeDateRangeInputFacet();
        $facet->relativeDateRange->base =
            new DateTime('2021-01-01T00:00:00+00:00');
        $facet->relativeDateRange->gap =
            new DateInterval('P1D');
        $facet->relativeDateRange->before =
            new DateInterval('P2D');
        $facet->relativeDateRange->after =
            new DateInterval('P4D');
        $facet->relativeDateRange->roundStart =
            DateRangeRound::START_OF_YEAR;
        $facet->relativeDateRange->roundEnd =
            DateRangeRound::END_OF_MONTH;
        $facet->excludeFilter = ['exclude'];

        $factory = new FacetListFactory();

        $facetList = $factory->create([$facet]);

        $this->assertEquals(
            [
                new RelativeDateRangeFacet(
                    'key',
                    new DateTime('2021-01-01T00:00:00+00:00'),
                    new DateInterval('P2D'),
                    new DateInterval('P4D'),
                    new DateInterval('P1D'),
                    \Atoolo\Search\Dto\Search\Query\DateRangeRound::START_OF_YEAR,
                    \Atoolo\Search\Dto\Search\Query\DateRangeRound::END_OF_MONTH,
                    ['exclude'],
                ),
            ],
            $facetList,
            'relative date range facet expected',
        );
    }
}
