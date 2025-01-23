<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\AbsoluteDateRangeInputFilter;
use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\InputGeoPoint;
use Atoolo\GraphQL\Search\Input\RelativeDateRangeInputFilter;
use Atoolo\GraphQL\Search\Input\SpatialArbitraryRectangleInputFilter;
use Atoolo\GraphQL\Search\Input\SpatialOrbitalInputFilter;
use Atoolo\GraphQL\Search\Query\FilterListFactory;
use Atoolo\GraphQL\Search\Types\SpatialOrbitalMode;
use Atoolo\Search\Dto\Search\Query\DateRangeRound;
use Atoolo\Search\Dto\Search\Query\Filter\AbsoluteDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\AndFilter;
use Atoolo\Search\Dto\Search\Query\Filter\CategoryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentSectionTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\GeoLocatedFilter;
use Atoolo\Search\Dto\Search\Query\Filter\GroupFilter;
use Atoolo\Search\Dto\Search\Query\Filter\IdFilter;
use Atoolo\Search\Dto\Search\Query\Filter\NotFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\OrFilter;
use Atoolo\Search\Dto\Search\Query\Filter\QueryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\RelativeDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SiteFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SourceFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SpatialArbitraryRectangleFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SpatialOrbitalFilter;
use Atoolo\Search\Dto\Search\Query\GeoPoint;
use DateInterval;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FilterListFactory::class)]
class FilterListFactoryTest extends TestCase
{
    public function testCreateObjectTypeFilter(): void
    {
        $filter = new InputFilter();
        $filter->objectTypes = ['content'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new ObjectTypeFilter(['content']),
            ],
            $filterList,
            'objectType filter expected',
        );
    }

    public function testCreateContentSectionTypeFilter(): void
    {
        $filter = new InputFilter();
        $filter->contentSectionTypes = ['youtube'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new ContentSectionTypeFilter(['youtube']),
            ],
            $filterList,
            'contentSectionType filter expected',
        );
    }

    public function testCreateCategoryFilter(): void
    {
        $filter = new InputFilter();
        $filter->categories = ['123'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new CategoryFilter(['123']),
            ],
            $filterList,
            'category filter expected',
        );
    }

    public function testCreateSiteFilter(): void
    {
        $filter = new InputFilter();
        $filter->sites = ['123'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new SiteFilter(['123']),
            ],
            $filterList,
            'site filter expected',
        );
    }

    public function testCreateSourceFilter(): void
    {
        $filter = new InputFilter();
        $filter->sources = ['internal'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new SourceFilter(['internal']),
            ],
            $filterList,
            'source filter expected',
        );
    }

    public function testCreateContentTypeFilter(): void
    {
        $filter = new InputFilter();
        $filter->contentTypes = ['html/text*'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new ContentTypeFilter(['html/text*']),
            ],
            $filterList,
            'contentType filter expected',
        );
    }

    public function testCreateAbsoluteDateRangeFilter(): void
    {
        $dateRangeFilterInput = new AbsoluteDateRangeInputFilter();
        $dateRangeFilterInput->from = new DateTime('2021-01-01T00:00:00+00:00');
        $dateRangeFilterInput->to = new DateTime('2021-01-02T00:00:00+00:00');


        $filter = new InputFilter();
        $filter->absoluteDateRange = $dateRangeFilterInput;

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new AbsoluteDateRangeFilter(
                    new DateTime('2021-01-01T00:00:00+00:00'),
                    new DateTime('2021-01-02T00:00:00+00:00'),
                ),
            ],
            $filterList,
            'site filter expected',
        );
    }

    public function testCreateRelativeDateRangeFilter(): void
    {
        $dateRangeFilterInput = new RelativeDateRangeInputFilter();
        $dateRangeFilterInput->before = new DateInterval('P1D');
        $dateRangeFilterInput->roundStart =
            \Atoolo\GraphQL\Search\Types\DateRangeRound::START_OF_MONTH;

        $filter = new InputFilter();
        $filter->relativeDateRange = $dateRangeFilterInput;

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new RelativeDateRangeFilter(
                    null,
                    new DateInterval('P1D'),
                    null,
                    DateRangeRound::START_OF_MONTH,
                    null,
                ),
            ],
            $filterList,
            'site filter expected',
        );
    }

    public function testCreateGroupFilter(): void
    {
        $filter = new InputFilter();
        $filter->groups = ['123'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new GroupFilter(['123']),
            ],
            $filterList,
            'group filter expected',
        );
    }

    public function testCreateIdFilter(): void
    {
        $filter = new InputFilter();
        $filter->ids = ['123'];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new IdFilter(['123']),
            ],
            $filterList,
            'id filter expected',
        );
    }

    public function testGeoLocatedFilter(): void
    {
        $filter = new InputFilter();
        $filter->geoLocated = true;

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new GeoLocatedFilter(true),
            ],
            $filterList,
            'geo-located filter expected',
        );
    }

    public function testCreateSpatialOrbitalFilter(): void
    {
        $centerPoint = new InputGeoPoint();
        $centerPoint->lng = 1;
        $centerPoint->lat = 2;
        $spatialOrbital = new SpatialOrbitalInputFilter();
        $spatialOrbital->centerPoint = $centerPoint;
        $spatialOrbital->distance = 10;
        $spatialOrbital->mode = SpatialOrbitalMode::BOUNDING_BOX;

        $filter = new InputFilter();
        $filter->spatialOrbital = $spatialOrbital;

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new SpatialOrbitalFilter(
                    10,
                    new GeoPoint(1, 2),
                    \Atoolo\Search\Dto\Search\Query\Filter\SpatialOrbitalMode::BOUNDING_BOX,
                ),
            ],
            $filterList,
            'spatialOrbitalFilter filter expected',
        );
    }

    public function testCreateSpatialOrbitalFilterWithMissingDistance(): void
    {
        $centerPoint = new InputGeoPoint();
        $centerPoint->lng = 1;
        $centerPoint->lat = 2;
        $spatialOrbital = new SpatialOrbitalInputFilter();
        $spatialOrbital->centerPoint = $centerPoint;
        $spatialOrbital->mode = SpatialOrbitalMode::BOUNDING_BOX;

        $filter = new InputFilter();
        $filter->spatialOrbital = $spatialOrbital;

        $this->expectException(InvalidArgumentException::class);
        $factory = new FilterListFactory();
        $factory->create([$filter]);
    }

    public function testCreateSpatialOrbitalFilterWithMissingCenterPoint(): void
    {
        $spatialOrbital = new SpatialOrbitalInputFilter();
        $spatialOrbital->distance = 10;
        $spatialOrbital->mode = SpatialOrbitalMode::BOUNDING_BOX;

        $filter = new InputFilter();
        $filter->spatialOrbital = $spatialOrbital;

        $this->expectException(InvalidArgumentException::class);
        $factory = new FilterListFactory();
        $factory->create([$filter]);
    }

    public function testCreateSpatialArbitraryRectangleFilter(): void
    {
        $lowerLeftCorner = new InputGeoPoint();
        $lowerLeftCorner->lng = 1;
        $lowerLeftCorner->lat = 2;

        $upperRightCorner = new InputGeoPoint();
        $upperRightCorner->lng = 3;
        $upperRightCorner->lat = 4;

        $spatialArbitraryRectangle = new SpatialArbitraryRectangleInputFilter();
        $spatialArbitraryRectangle->lowerLeftCorner = $lowerLeftCorner;
        $spatialArbitraryRectangle->upperRightCorner = $upperRightCorner;

        $filter = new InputFilter();
        $filter->key = 'geo';
        $filter->spatialArbitraryRectangle = $spatialArbitraryRectangle;

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new SpatialArbitraryRectangleFilter(
                    new GeoPoint(1, 2),
                    new GeoPoint(3, 4),
                    'geo',
                ),
            ],
            $filterList,
            'spatialArbitraryRectangle filter expected',
        );
    }

    public function testCreateSpatialArbitraryRectangleFilterWithMissingLowerLeftCorner(): void
    {
        $upperRightCorner = new InputGeoPoint();
        $upperRightCorner->lng = 3;
        $upperRightCorner->lat = 4;

        $spatialArbitraryRectangle = new SpatialArbitraryRectangleInputFilter();
        $spatialArbitraryRectangle->upperRightCorner = $upperRightCorner;

        $filter = new InputFilter();
        $filter->key = 'geo';
        $filter->spatialArbitraryRectangle = $spatialArbitraryRectangle;

        $this->expectException(InvalidArgumentException::class);
        $factory = new FilterListFactory();
        $factory->create([$filter]);
    }

    public function testCreateSpatialArbitraryRectangleFilterWithMissingUpperRightCorner(): void
    {
        $lowerLeftCorner = new InputGeoPoint();
        $lowerLeftCorner->lng = 1;
        $lowerLeftCorner->lat = 2;

        $spatialArbitraryRectangle = new SpatialArbitraryRectangleInputFilter();
        $spatialArbitraryRectangle->lowerLeftCorner = $lowerLeftCorner;

        $filter = new InputFilter();
        $filter->key = 'geo';
        $filter->spatialArbitraryRectangle = $spatialArbitraryRectangle;

        $this->expectException(InvalidArgumentException::class);
        $factory = new FilterListFactory();
        $factory->create([$filter]);
    }

    public function testCreateAndFilter(): void
    {
        $andFilter = new InputFilter();
        $filterA = new InputFilter();
        $filterA->objectTypes = ['content'];
        $filterB = new InputFilter();
        $filterB->contentSectionTypes = ['youtube'];

        $andFilter->and = [$filterA, $filterB];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$andFilter]);

        $this->assertEquals(
            [
                new AndFilter([
                    new ObjectTypeFilter(['content']),
                    new ContentSectionTypeFilter(['youtube']),
                ]),
            ],
            $filterList,
            'and filter expected',
        );
    }

    public function testCreateOrFilter(): void
    {
        $orFilter = new InputFilter();
        $filterA = new InputFilter();
        $filterA->objectTypes = ['content'];
        $filterB = new InputFilter();
        $filterB->contentSectionTypes = ['youtube'];

        $orFilter->or = [$filterA, $filterB];

        $factory = new FilterListFactory();
        $filterList = $factory->create([$orFilter]);

        $this->assertEquals(
            [
                new OrFilter([
                    new ObjectTypeFilter(['content']),
                    new ContentSectionTypeFilter(['youtube']),
                ]),
            ],
            $filterList,
            'or filter expected',
        );
    }

    public function testCreatNotFilter(): void
    {
        $notFilter = new InputFilter();
        $filter = new InputFilter();
        $filter->objectTypes = ['content'];

        $notFilter->not = $filter;

        $factory = new FilterListFactory();
        $filterList = $factory->create([$notFilter]);

        $this->assertEquals(
            [
                new NotFilter(new ObjectTypeFilter(['content'])),
            ],
            $filterList,
            'not filter expected',
        );
    }

    public function testCreateQueryFilter(): void
    {
        $filter = new InputFilter();
        $filter->query = 'test:test';

        $factory = new FilterListFactory();
        $filterList = $factory->create([$filter]);

        $this->assertEquals(
            [
                new QueryFilter('test:test'),
            ],
            $filterList,
            'query filter expected',
        );
    }

    public function testCreateWithInvalidInputFilter(): void
    {

        $filter = new InputFilter();
        $factory = new FilterListFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create([$filter]);
    }
}
