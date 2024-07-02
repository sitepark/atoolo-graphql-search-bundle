<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Search
{
    private readonly SearchQueryFactory $factory;

    public function __construct(
        private readonly \Atoolo\Search\Search $search,
    ) {
        $this->factory = new SearchQueryFactory();
    }

    #[GQL\Query(name: 'search', type: 'SearchResult!')]
    public function search(SearchInput $input): SearchResult
    {
        $query = $this->factory->create($input);
        return $this->search->search($query);
    }
}
