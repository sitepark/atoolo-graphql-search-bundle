<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\GraphQL\Search\Query\MoreLikeThis;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Rewrite\Service\UrlRewriteContext;
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
        $input->urlBasePath = '/test';

        $query = new MoreLikeThisQuery(
            id: '123',
            lang: ResourceLanguage::default(),
        );

        $searcher = $this->createMock(\Atoolo\Search\MoreLikeThis::class);
        $searcher->expects($this->once())
            ->method('moreLikeThis')
            ->with($query)
            ->willReturn($this->createMock(SearchResult::class));

        $urlRewriteContext = $this->createMock(UrlRewriteContext::class);
        $urlRewriteContext->expects($this->once())
            ->method('setBasePath')
            ->with('/test');
        $select = new MoreLikeThis($searcher, $urlRewriteContext);
        $select->moreLikeThis($input);
    }
}
