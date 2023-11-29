<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SelectQueryInput;
use Atoolo\GraphQL\Search\Types\Resource;
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
     * @return Resource[]
     */
    #[GQL\Query(name: 'selectResources', type: 'ResourceSearchResult!')]
    public function selectResources(SelectQueryInput $input): iterable
    {
        $factory = new SelectQueryFactory();
        $query = $factory->create($input);
        return $this->searcher->select($query);
    }
}
