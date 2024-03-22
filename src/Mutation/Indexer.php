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
        private readonly ?PhpLimitIncreaser $limitIncreaser = null
    ) {
    }

    #[GQL\Mutation(name: 'index', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function index(IndexerInput $input): IndexerStatus
    {
        $this->limitIncreaser?->increase();
        try {
            $parameter = new IndexerParameter(
                $input->cleanupThreshold ?? 0,
                $input->chunkSize ?? 500,
                $input->paths ?? []
            );
            return $this->indexer->index($parameter);
        } finally {
            $this->limitIncreaser?->reset();
        }
    }


    /**
     * @param string[] $idList
     */
    #[GQL\Mutation(name: 'indexRemove', type: 'Boolean!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    #[GQL\Arg(
        name:"idList",
        type:"[String!]",
        description:"list of id's of the entries to be deleted"
    )]
    public function indexRemove(array $idList): bool
    {
        $this->indexer->remove($idList);
        return true; // graphql requires a return value
    }

    #[GQL\Mutation(name: 'indexAbort', type: 'Boolean!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function indexAbort(): bool
    {
        $this->indexer->abort();
        return true; // graphql requires a return value
    }
}
