<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\GraphQL\Search\Query\Search;
use Atoolo\GraphQL\Search\Query\Suggest;
use Atoolo\GraphQL\Search\Service\SolrInputGuard;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\SuggestQuery;
use GraphQL\Error\UserError;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(Suggest::class)]
class SuggestTest extends TestCase
{
    public function testSuggest(): void
    {

        $query = new SuggestQuery('test', ResourceLanguage::default());

        $searcher = $this->createMock(\Atoolo\Search\Suggest::class);
        $searcher->expects($this->once())
            ->method('suggest')
            ->with($query);

        $input = new SuggestInput();
        $input->text = 'test';

        $suggest = new Suggest($searcher);
        $suggest->suggest($input);
    }

    public function testSuggestWithException(): void
    {
        $searcher = $this->createMock(\Atoolo\Search\Suggest::class);
        $searcher->expects($this->once())
            ->method('suggest')
            ->willThrowException(new \Exception('Solr HTTP error: internal details'));

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Solr HTTP error: internal details'));

        $input = new SuggestInput();
        $input->text = 'test';

        $suggest = new Suggest($searcher, $logger);

        $this->expectException(UserError::class);
        $this->expectExceptionMessage(Search::ERROR_MESSAGE);
        $suggest->suggest($input);
    }

    public function testSuggestWithUnsafeInput(): void
    {
        $searcher = $this->createMock(\Atoolo\Search\Suggest::class);
        $searcher->expects($this->never())
            ->method('suggest');

        $input = new SuggestInput();
        $input->text = '{!lucene}a';

        $suggest = new Suggest($searcher);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(SolrInputGuard::ERROR_MESSAGE);
        $suggest->suggest($input);
    }
}
