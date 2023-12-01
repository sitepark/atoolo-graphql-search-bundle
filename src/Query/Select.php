<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\GraphQL\Search\Types\Resource;
use Atoolo\Search\Dto\Search\Result\ResourceSearchResult;
use Atoolo\Search\SelectSearcher;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Select
{
    public function __construct(
        private readonly SelectSearcher $searcher
    ) {
    }

    /**
     * @return ResourceSearchResult
     */
    #[GQL\Query(name: 'search', type: 'ResourceSearchResult!')]
    public function search(SearchInput $input): ResourceSearchResult
    {
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);
        return $this->searcher->select($query);
    }
}
