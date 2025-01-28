<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputBoosting;
use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\InputGeoPoint;
use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\SearchQueryFactory;
use Atoolo\GraphQL\Search\Types\QueryOperator;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\Boosting;
use Atoolo\Search\Dto\Search\Query\Facet\ObjectTypeFacet;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\GeoPoint;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SearchQueryFactory::class)]
class SearchQueryFactoryTest extends TestCase
{
    public function testCreateWithLimit(): void
    {
        $input = new SearchInput();
        $input->limit = 10;
        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            10,
            $query->limit,
            'limit expected',
        );
    }

    public function testCreateWithAndQueryOperator(): void
    {
        $input = new SearchInput();
        $input->defaultQueryOperator = QueryOperator::AND;
        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            \Atoolo\Search\Dto\Search\Query\QueryOperator::AND,
            $query->defaultQueryOperator,
            'limit expected',
        );
    }

    public function testCreateWithOffset(): void
    {
        $input = new SearchInput();
        $input->offset = 10;
        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            10,
            $query->offset,
            'offset expected',
        );
    }

    public function testCreateWithTextFilter(): void
    {
        $input = new SearchInput();
        $input->text = 'text';
        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            'text',
            $query->text,
            'text expected',
        );
    }

    public function testCreateWithTextFilterWithOrOperator(): void
    {
        $input = new SearchInput();
        $input->text = 'text';
        $input->defaultQueryOperator = QueryOperator::OR;

        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            \Atoolo\Search\Dto\Search\Query\QueryOperator::OR,
            $query->defaultQueryOperator,
            'text expected',
        );
    }

    public function testCreateWithSortName(): void
    {
        $factory = new SearchQueryFactory();

        $sort = new InputSortCriteria();
        $sort->name = SortDirection::ASC;

        $input = new SearchInput();
        $input->sort = [$sort];

        $query = $factory->create($input);

        $this->assertEquals(
            [new Name(Direction::ASC)],
            $query->sort,
            'name sort expected',
        );
    }

    public function testCreateWithFilter(): void
    {
        $input = new SearchInput();
        $filter = new InputFilter();
        $filter->objectTypes = ['content'];
        $input->filter = [$filter];

        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [
                new ObjectTypeFilter(['content']),
            ],
            $query->filter,
            'filter expected',
        );
    }

    public function testCreateWithFacet(): void
    {
        $input = new SearchInput();

        $facet = new InputFacet();
        $facet->key = 'objectTypes';
        $facet->objectTypes = ['content'];

        $input->facets = [$facet];

        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new ObjectTypeFacet('objectTypes', ['content'])],
            $query->facets,
            'facets expected',
        );
    }

    public function testCreateWithTimeZone(): void
    {
        $input = new SearchInput();
        $input->timeZone = new \DateTimeZone('Europe/Berlin');

        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            new \DateTimeZone('Europe/Berlin'),
            $query->timeZone,
            'timeZone expected',
        );
    }

    public function testCreateWithBoosting(): void
    {
        $input = new SearchInput();
        $input->boosting = new InputBoosting();
        $input->boosting->queryFields = ['sp_title^2'];

        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            new Boosting(
                queryFields: ['sp_title^2'],
            ),
            $query->boosting,
            'boosting expected',
        );
    }

    public function testCreateWithDistanceReferencePoint(): void
    {
        $input = new SearchInput();
        $input->distanceReferencePoint = new InputGeoPoint();
        $input->distanceReferencePoint->lng = 1;
        $input->distanceReferencePoint->lat = 2;

        $factory = new SearchQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            new GeoPoint(1, 2),
            $query->distanceReferencePoint,
            'distanceReferencePoint expected',
        );
    }
}
