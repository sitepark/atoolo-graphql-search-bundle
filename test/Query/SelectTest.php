<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\Search;
use Atoolo\Search\Dto\Search\Query\SearchQueryBuilder;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Search::class)]
class SelectTest extends TestCase
{
    public function testSearch(): void
    {
        $input = new SearchInput();
        $input->text = 'query';

        $builder = new SearchQueryBuilder();
        $query = $builder
            ->text('query')
            ->build();

        $searcher = $this->createMock(\Atoolo\Search\Search::class);
        $searcher->expects($this->once())
            ->method('search')
            ->with($query)
            ->willReturn($this->createMock(SearchResult::class));

        $select = new Search($searcher);
        $select->search($input);
    }
}
