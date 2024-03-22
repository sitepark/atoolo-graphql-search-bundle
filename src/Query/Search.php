<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Search
{
    public function __construct(
        private readonly \Atoolo\Search\Search $search
    ) {
    }

    #[GQL\Query(name: 'search', type: 'SearchResult!')]
    public function search(SearchInput $input): SearchResult
    {
        $factory = new SearchQueryFactory();
        $query = $factory->create($input);
        return $this->search->search($query);
    }
}
