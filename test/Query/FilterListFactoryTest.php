<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\AbsoluteDateRangeInputFilter;
use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\RelativeDateRangeInputFilter;
use Atoolo\GraphQL\Search\Query\FilterListFactory;
use Atoolo\Search\Dto\Search\Query\DateRangeRound;
use Atoolo\Search\Dto\Search\Query\Filter\AbsoluteDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\AndFilter;
use Atoolo\Search\Dto\Search\Query\Filter\CategoryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ContentSectionTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\GroupFilter;
use Atoolo\Search\Dto\Search\Query\Filter\IdFilter;
use Atoolo\Search\Dto\Search\Query\Filter\NotFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\OrFilter;
use Atoolo\Search\Dto\Search\Query\Filter\QueryFilter;
use Atoolo\Search\Dto\Search\Query\Filter\RelativeDateRangeFilter;
use Atoolo\Search\Dto\Search\Query\Filter\SiteFilter;
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
