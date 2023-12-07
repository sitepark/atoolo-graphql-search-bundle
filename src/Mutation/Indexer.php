<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Mutation;

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

    #[GQL\Mutation(name: 'index', type: 'String!')]
    #[GQL\Access("hasRole('ROLE_API')")]
    public function index(IndexerInput $input): string
    {
        $parameter = new IndexerParameter(
            $input->index,
            $input->cleanupThreshold ?? 0,
            $input->paths ?? []
        );
        return $this->indexer->index($parameter);
    }
}
