<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\Select;
use Atoolo\Search\Dto\Search\Query\SelectQueryBuilder;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Atoolo\Search\SelectSearcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Select::class)]
class SelectTest extends TestCase
{
    public function testSearch(): void
    {
        $input = new SearchInput();
        $input->index = 'index';
        $input->text = 'query';

        $builder = new SelectQueryBuilder();
        $query = $builder
            ->index('index')
            ->text('query')
            ->build();

        $searcher = $this->createMock(SelectSearcher::class);
        $searcher->expects($this->once())
            ->method('select')
            ->with($query)
            ->willReturn($this->createMock(SearchResult::class));

        $select = new Select($searcher);
        $select->search($input);
    }
}
