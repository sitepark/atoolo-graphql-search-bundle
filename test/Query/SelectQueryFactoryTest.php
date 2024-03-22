<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputFacet;
use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\SelectQueryFactory;
use Atoolo\GraphQL\Search\Types\QueryOperator;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\Facet\ObjectTypeFacet;
use Atoolo\Search\Dto\Search\Query\Filter\ArchiveFilter;
use Atoolo\Search\Dto\Search\Query\Filter\ObjectTypeFilter;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Headline;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SelectQueryFactory::class)]
class SelectQueryFactoryTest extends TestCase
{
    public function testCreateWithIndex(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            'index',
            $query->index,
            'index expected'
        );
    }

    public function testCreateWithLimit(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $input->limit = 10;
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            10,
            $query->limit,
            'limit expected'
        );
    }

    public function testCreateWithOffset(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $input->offset = 10;
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            10,
            $query->offset,
            'offset expected'
        );
    }

    public function testCreateWithTextFilter(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $input->text = 'text';
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            'text',
            $query->text,
            'text expected'
        );
    }

    public function testCreateWithTextFilterWithOrOperator(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $input->text = 'text';
        $input->defaultQueryOperator = QueryOperator::OR;

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            \Atoolo\Search\Dto\Search\Query\QueryOperator::OR,
            $query->defaultQueryOperator,
            'text expected'
        );
    }

    public function testCreateWithSortName(): void
    {
        $input = $this->createSearchInputWithSort('name');

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new Name(Direction::ASC)],
            $query->sort,
            'name sort expected'
        );
    }

    public function testCreateWithSortHeadline(): void
    {
        $input = $this->createSearchInputWithSort('headline');

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new Headline(Direction::ASC)],
            $query->sort,
            'headline sort expected'
        );
    }

    public function testCreateWithSortDate(): void
    {
        $input = $this->createSearchInputWithSort('date');

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new Date(Direction::ASC)],
            $query->sort,
            'date sort expected'
        );
    }

    public function testCreateWithSortNatural(): void
    {
        $input = $this->createSearchInputWithSort('natural');

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new Natural(Direction::ASC)],
            $query->sort,
            'natural sort expected'
        );
    }

    public function testCreateWithSortScore(): void
    {
        $input = $this->createSearchInputWithSort('score');

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new Score(Direction::ASC)],
            $query->sort,
            'score sort expected'
        );
    }

    public function testCreateWithInvalidSort(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $input->sort = [new InputSortCriteria()];

        $factory = new SelectQueryFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create($input);
    }

    private function createSearchInputWithSort(string $name): SearchInput
    {
        $sort = new InputSortCriteria();
        $sort->$name = SortDirection::ASC;

        $input = new SearchInput();
        $input->index = 'index';
        $input->sort = [$sort];

        return $input;
    }

    public function testCreateWithFilter(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $filter = new InputFilter();
        $filter->objectTypes = ['content'];
        $input->filter = [$filter];

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [
                new ObjectTypeFilter(['content']),
                new ArchiveFilter()
            ],
            $query->filter,
            'filter expected'
        );
    }

    public function testCreateWithFacet(): void
    {
        $input = new SearchInput();
        $input->index = 'index';

        $facet = new InputFacet();
        $facet->key = 'objectTypes';
        $facet->objectTypes = ['content'];

        $input->facets = [$facet];

        $factory = new SelectQueryFactory();
        $query = $factory->create($input);

        $this->assertEquals(
            [new ObjectTypeFacet('objectTypes', ['content'])],
            $query->facets,
            'facets expected'
        );
    }
}
