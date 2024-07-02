<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\Search\Dto\Indexer\IndexerStatus;
use Atoolo\Search\Service\Indexer\InternalResourceIndexer;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[GQL\Provider]
class Indexer
{
    public function __construct(
        private readonly InternalResourceIndexer $indexer,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    #[GQL\Query(name: 'indexerStatus', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function indexerStatus(): IndexerStatus
    {
        return $this->indexer->getStatus();
    }
}
