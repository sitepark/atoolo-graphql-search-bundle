<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\IndexerInput;
use Atoolo\Search\Dto\Indexer\IndexerParameter;
use Atoolo\Search\Service\Indexer\BackgroundIndexer;
use Atoolo\Search\Service\Indexer\BackgroundIndexerStatus;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Indexer
{
    public function __construct(
        private readonly BackgroundIndexer $indexer
    ) {
    }

    #[GQL\Query(name: 'getIndexerStatus', type: 'IndexerStatus')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function getIndexerStatus(string $index): ?BackgroundIndexerStatus
    {
        return $this->indexer->getStatus($index);
    }
}
