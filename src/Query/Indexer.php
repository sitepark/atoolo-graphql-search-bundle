<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\Search\Dto\Indexer\IndexerStatus;
use Atoolo\Search\Service\Indexer\BackgroundIndexer;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Indexer
{
    public function __construct(
        private readonly BackgroundIndexer $indexer
    ) {
    }

    #[GQL\Query(name: 'indexerStatus', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function indexerStatus(string $index): IndexerStatus
    {
        return $this->indexer->getStatus($index);
    }
}
