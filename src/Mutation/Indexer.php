<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Mutation;

use Atoolo\GraphQL\Search\Input\IndexerInput;
use Atoolo\GraphQL\Search\Service\PhpLimitIncreaser;
use Atoolo\Search\Dto\Indexer\IndexerParameter;
use Atoolo\Search\Dto\Indexer\IndexerStatus;
use Atoolo\Search\Service\Indexer\BackgroundIndexer;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Indexer
{
    public function __construct(
        private readonly BackgroundIndexer $indexer,
        private readonly PhpLimitIncreaser $limitIncreaser
    ) {
    }

    #[GQL\Mutation(name: 'index', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function index(IndexerInput $input): IndexerStatus
    {
        $this->limitIncreaser->increase();
        try {
            $parameter = new IndexerParameter(
                $input->index,
                $input->cleanupThreshold,
                $input->chunkSize,
                $input->paths ?? []
            );
            return $this->indexer->index($parameter);
        } finally {
            $this->limitIncreaser->reset();
        }
    }


    /**
     * @param string[] $idList
     */
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
