<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Mutation;

use Atoolo\GraphQL\Search\Input\IndexerInput;
use Atoolo\GraphQL\Search\Mutation\Indexer;
use Atoolo\Search\Service\Indexer\BackgroundIndexer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Indexer::class)]
class IndexerTest extends TestCase
{
    public function testIndexer(): void
    {
        $backgroundIndexer = $this->createMock(BackgroundIndexer::class);
        $backgroundIndexer->expects($this->once())
            ->method('index');

        $input = new IndexerInput();
        $input->index = 'index';
        $input->cleanupThreshold = 1;
        $input->chunkSize = 10;

        $indexer = new Indexer($backgroundIndexer);
        $indexer->index($input);
    }

    public function testRemove(): void
    {
        $backgroundIndexer = $this->createMock(BackgroundIndexer::class);
        $backgroundIndexer->expects($this->once())
            ->method('remove');

        $indexer = new Indexer($backgroundIndexer);
        $indexer->indexRemove('index', ['123']);
    }

    public function testAbort(): void
    {
        $backgroundIndexer = $this->createMock(BackgroundIndexer::class);
        $backgroundIndexer->expects($this->once())
            ->method('abort');

        $indexer = new Indexer($backgroundIndexer);
        $indexer->indexAbort('index', '123');
    }
}
