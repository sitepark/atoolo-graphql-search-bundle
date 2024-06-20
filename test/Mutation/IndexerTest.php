<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Mutation;

use Atoolo\GraphQL\Search\Mutation\Indexer;
use Atoolo\GraphQL\Search\Service\PhpLimitIncreaser;
use Atoolo\Search\Service\Indexer\InternalResourceIndexer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Indexer::class)]
class IndexerTest extends TestCase
{
    public function testIndexer(): void
    {
        $indexer = $this->createMock(InternalResourceIndexer::class);
        $indexer->expects($this->once())
            ->method('index');

        $indexer = new Indexer($indexer);
        $indexer->index();
    }

    public function testUpdate(): void
    {
        $indexer = $this->createMock(InternalResourceIndexer::class);
        $indexer->expects($this->once())
            ->method('update');

        $indexer = new Indexer($indexer);
        $indexer->indexUpdate(['/index.php']);
    }

    public function testRemove(): void
    {
        $indexer = $this->createMock(InternalResourceIndexer::class);
        $indexer->expects($this->once())
            ->method('remove');

        $indexer = new Indexer($indexer);
        $indexer->indexRemove(['123']);
    }

    public function testAbort(): void
    {
        $indexer = $this->createMock(InternalResourceIndexer::class);
        $indexer->expects($this->once())
            ->method('abort');

        $indexer = new Indexer($indexer);
        $indexer->indexAbort('index', '123');
    }
}
