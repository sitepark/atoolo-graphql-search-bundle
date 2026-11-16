<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Query\Search;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\Search\Dto\Search\Query\SearchQueryBuilder;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use GraphQL\Error\UserError;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

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
            ->willThrowException(new \Exception('Solr HTTP error: internal details'));

        $contextDispatcher = $this->createMock(ContextDispatcher::class);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Solr HTTP error: internal details'));

        $select = new Search($searcher, $contextDispatcher, $logger);

        $this->expectException(UserError::class);
        $this->expectExceptionMessage(Search::ERROR_MESSAGE);
        $select->search($input);
    }

    public function testSearchWithUnsafeInput(): void
    {
        $filter = new InputFilter();
        $filter->query = '{!lucene}a';
        $input = new SearchInput();
        $input->filter = [$filter];

        $searcher = $this->createMock(\Atoolo\Search\Search::class);
        $searcher->expects($this->never())
            ->method('search');

        $contextDispatcher = $this->createMock(ContextDispatcher::class);

        $select = new Search($searcher, $contextDispatcher);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(SolrInputGuard::ERROR_MESSAGE);
        $select->search($input);
    }
}
