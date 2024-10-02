<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputSortCriteria;
use Atoolo\GraphQL\Search\Query\SortCriteriaFactory;
use Atoolo\GraphQL\Search\Types\SortDirection;
use Atoolo\Search\Dto\Search\Query\Sort\Date;
use Atoolo\Search\Dto\Search\Query\Sort\Direction;
use Atoolo\Search\Dto\Search\Query\Sort\Headline;
use Atoolo\Search\Dto\Search\Query\Sort\Name;
use Atoolo\Search\Dto\Search\Query\Sort\Natural;
use Atoolo\Search\Dto\Search\Query\Sort\Score;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SortCriteriaFactory::class)]
class SortCriteriaFactoryTest extends TestCase
{
    public function testCreateWithSortName(): void
    {
        $criteria = $this->createSearchInputWithSort('name');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Name(Direction::ASC),
            $sort,
            'name sort expected',
        );
    }

    public function testCreateWithSortHeadline(): void
    {
        $criteria = $this->createSearchInputWithSort('headline');

        $factory = new SortCriteriaFactory();

        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Headline(Direction::ASC),
            $sort,
            'headline sort expected',
        );
    }

    public function testCreateWithSortDate(): void
    {
        $criteria = $this->createSearchInputWithSort('date');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Date(Direction::ASC),
            $sort,
            'date sort expected',
        );
    }

    public function testCreateWithSortNatural(): void
    {
        $criteria = $this->createSearchInputWithSort('natural');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Natural(Direction::ASC),
            $sort,
            'natural sort expected',
        );
    }

    public function testCreateWithSortScore(): void
    {
        $criteria = $this->createSearchInputWithSort('score');

        $factory = new SortCriteriaFactory();
        $sort = $factory->create($criteria);

        $this->assertEquals(
            new Score(Direction::ASC),
            $sort,
            'score sort expected',
        );
    }

    public function testCreateWithInvalidSort(): void
    {
        $sort = new InputSortCriteria();

        $factory = new SortCriteriaFactory();

        $this->expectException(InvalidArgumentException::class);
        $factory->create($sort);
    }

    private function createSearchInputWithSort(string $name): InputSortCriteria
    {
        $sort = new InputSortCriteria();
        $sort->$name = SortDirection::ASC;
        return $sort;
    }
}
