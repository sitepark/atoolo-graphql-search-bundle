<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Query\MoreLikeThis;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\MoreLikeThisQuery;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

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
}
