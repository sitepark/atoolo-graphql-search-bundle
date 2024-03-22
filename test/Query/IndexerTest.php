<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Query\Indexer;
use Atoolo\Search\Dto\Indexer\IndexerStatus;
use Atoolo\Search\Service\Indexer\BackgroundIndexer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Indexer::class)]
class IndexerTest extends TestCase
{
    public function testIndexerStatus(): void
    {
        $backgroundIndexer = $this->createMock(BackgroundIndexer::class);
        $backgroundIndexer->expects($this->once())
            ->method('getStatus')
            ->with('index')
            ->willReturn($this->createMock(IndexerStatus::class));

        $indexer = new Indexer($backgroundIndexer);
        $indexer->indexerStatus('index');
    }
}
