<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Mutation;

use Atoolo\GraphQL\Search\Input\IndexerInput;
use Atoolo\Search\Dto\Indexer\IndexerParameter;
use Atoolo\Search\Dto\Indexer\IndexerStatus;
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

    #[GQL\Mutation(name: 'index', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function index(IndexerInput $input): IndexerStatus
    {
        set_time_limit(60 * 60 * 2);
        $oldLimit = ini_get('memory_limit');
        ini_set('memory_limit', '512M');
        try {
            $parameter = new IndexerParameter(
                $input->index,
                $input->cleanupThreshold ?? 0,
                $input->chunkSize ?? 500,
                $input->paths ?? []
            );
            return $this->indexer->index($parameter);
        } finally {
            ini_set('memory_limit', $oldLimit);
        }
    }

    #[GQL\Mutation(name: 'indexRemove', type: 'Boolean!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    #[GQL\Arg(
        name:"index",
        type:"String!",
        description:"index from which the entry is to be deleted"
    )]
    #[GQL\Arg(
        name:"idList",
        type:"[String!]",
        description:"list of id's of the entries to be deleted"
    )]
    /**
     * @param string[] $idList
     */
    public function indexRemove(string $index, array $idList): bool
    {
        $this->indexer->remove($index, $idList);
        return true;
    }

    #[GQL\Mutation(name: 'indexAbort', type: 'Boolean!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function indexAbort(string $index): bool
    {
        $this->indexer->abort($index);
        return true;
    }
}
