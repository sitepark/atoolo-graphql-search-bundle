<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Mutation;

use Atoolo\GraphQL\Search\Service\PhpLimitIncreaser;
use Atoolo\Search\Dto\Indexer\IndexerStatus;
use Atoolo\Search\Service\Indexer\InternalResourceIndexer;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Indexer
{
    public function __construct(
        private readonly InternalResourceIndexer $indexer,
        private readonly ?PhpLimitIncreaser $limitIncreaser = null
    ) {
    }

    #[GQL\Mutation(name: 'index', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function index(): IndexerStatus
    {
        $this->limitIncreaser?->increase();
        try {
            return $this->indexer->index();
        } finally {
            $this->limitIncreaser?->reset();
        }
    }

    /**
     * @param array<string> $paths
     */
    #[GQL\Mutation(name: 'indexUpdate', type: 'IndexerStatus!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    #[GQL\Arg(
        name:"paths",
        type:"[String!]!",
        description:"List of resource paths that are to be updated."
    )]
    public function indexUpdate(array $paths): IndexerStatus
    {
        $this->limitIncreaser?->increase();
        try {
            return $this->indexer->update($paths);
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
