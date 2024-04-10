<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Mutation;

use Atoolo\GraphQL\Search\Input\IndexerInput;
use Atoolo\GraphQL\Search\Mutation\Indexer;
use Atoolo\GraphQL\Search\Service\PhpLimitIncreaser;
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
        $limitIncreaser = $this->createStub(PhpLimitIncreaser::class);

        $indexer = new Indexer($backgroundIndexer, $limitIncreaser);
        $indexer->index();
    }

    public function testRemove(): void
    {
        $backgroundIndexer = $this->createMock(BackgroundIndexer::class);
        $limitIncreaser = $this->createStub(PhpLimitIncreaser::class);
        $backgroundIndexer->expects($this->once())
            ->method('remove');

        $indexer = new Indexer($backgroundIndexer, $limitIncreaser);
        $indexer->indexRemove(['123']);
    }

    public function testAbort(): void
    {
        $backgroundIndexer = $this->createMock(BackgroundIndexer::class);
        $limitIncreaser = $this->createStub(PhpLimitIncreaser::class);
        $backgroundIndexer->expects($this->once())
            ->method('abort');

        $indexer = new Indexer($backgroundIndexer, $limitIncreaser);
        $indexer->indexAbort('index', '123');
    }
}
