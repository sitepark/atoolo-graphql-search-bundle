<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Atoolo\Search\SelectSearcher;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Select
{
    public function __construct(
        private readonly SelectSearcher $searcher
    ) {
    }

    #[GQL\Query(name: 'search', type: 'SearchResult!')]
    public function search(SearchInput $input): SearchResult
    {
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);
        return $this->searcher->select($query);
    }
}
