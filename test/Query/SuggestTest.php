<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\GraphQL\Search\Query\Suggest;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\SuggestQuery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

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
}
