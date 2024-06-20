<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Query\Indexer;
use Atoolo\Search\Dto\Indexer\IndexerStatus;
use Atoolo\Search\Service\Indexer\InternalResourceIndexer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Indexer::class)]
class IndexerTest extends TestCase
{
    public function testIndexerStatus(): void
    {
        $indexer = $this->createMock(InternalResourceIndexer::class);
        $indexer->expects($this->once())
            ->method('getStatus')
            ->willReturn($this->createMock(IndexerStatus::class));

        $indexer = new Indexer($indexer);
        $indexer->indexerStatus();
    }
}
