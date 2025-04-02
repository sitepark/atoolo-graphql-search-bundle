<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Query\Search;
use Atoolo\Search\Dto\Search\Query\SearchQueryBuilder;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use GraphQL\Error\UserError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Search::class)]
class SearchTest extends TestCase
{
    public function testSearch(): void
    {
        $input = new SearchInput();
        $input->text = 'query';
        $context = new SearchContextInput();
        $context->urlBasePath = '/test';
        $input->context = $context;

        $builder = new SearchQueryBuilder();
        $query = $builder
            ->text('query')
            ->build();

        $searcher = $this->createMock(\Atoolo\Search\Search::class);
        $searcher->expects($this->once())
            ->method('search')
            ->with($query)
            ->willReturn($this->createMock(SearchResult::class));

        $contextDispatcher = $this->createMock(ContextDispatcher::class);
        $contextDispatcher->expects($this->once())
            ->method('dispatch');

        $select = new Search($searcher, $contextDispatcher);
        $select->search($input);
    }

    public function testSearchWithException(): void
    {
        $input = new SearchInput();
        $input->text = 'query';

        $searcher = $this->createMock(\Atoolo\Search\Search::class);
        $searcher->expects($this->once())
            ->method('search')
            ->willThrowException(new \Exception('test'));

        $contextDispatcher = $this->createMock(ContextDispatcher::class);

        $select = new Search($searcher, $contextDispatcher);

        $this->expectException(UserError::class);
        $select->search($input);
    }

}
