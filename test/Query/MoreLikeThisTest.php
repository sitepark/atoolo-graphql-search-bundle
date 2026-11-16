<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\InputFilter;
use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Query\MoreLikeThis;
use Atoolo\GraphQL\Search\Query\Search;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\MoreLikeThisQuery;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use GraphQL\Error\UserError;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(MoreLikeThis::class)]
class MoreLikeThisTest extends TestCase
{
    public function testMoreLikeThis(): void
    {
        $input = new MoreLikeThisInput();
        $input->id = '123';
        $context = new SearchContextInput();
        $context->urlBasePath = '/test';
        $input->context = $context;

        $query = new MoreLikeThisQuery(
            id: '123',
            lang: ResourceLanguage::default(),
        );

        $searcher = $this->createMock(\Atoolo\Search\MoreLikeThis::class);
        $searcher->expects($this->once())
            ->method('moreLikeThis')
            ->with($query)
            ->willReturn($this->createMock(SearchResult::class));

        $contextDispatcher = $this->createMock(ContextDispatcher::class);
        $contextDispatcher->expects($this->once())
            ->method('dispatch');

        $select = new MoreLikeThis($searcher, $contextDispatcher);
        $select->moreLikeThis($input);
    }

    public function testMoreLikeThisWithException(): void
    {
        $input = new MoreLikeThisInput();
        $input->id = '123';

        $searcher = $this->createMock(\Atoolo\Search\MoreLikeThis::class);
        $searcher->expects($this->once())
            ->method('moreLikeThis')
            ->willThrowException(new \Exception('Solr HTTP error: internal details'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Solr HTTP error: internal details'));

        $select = new MoreLikeThis(
            $searcher,
            $this->createMock(ContextDispatcher::class),
            $logger,
        );

        $this->expectException(UserError::class);
        $this->expectExceptionMessage(Search::ERROR_MESSAGE);
        $select->moreLikeThis($input);
    }

    public function testMoreLikeThisWithUnsafeInput(): void
    {
        $filter = new InputFilter();
        $filter->query = '{!lucene}a';
        $input = new MoreLikeThisInput();
        $input->id = '123';
        $input->filter = [$filter];

        $searcher = $this->createMock(\Atoolo\Search\MoreLikeThis::class);
        $searcher->expects($this->never())
            ->method('moreLikeThis');

        $select = new MoreLikeThis(
            $searcher,
            $this->createMock(ContextDispatcher::class),
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(SolrInputGuard::ERROR_MESSAGE);
        $select->moreLikeThis($input);
    }
}
