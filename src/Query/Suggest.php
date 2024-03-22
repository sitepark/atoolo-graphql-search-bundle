<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\Search\Dto\Search\Result\SuggestResult;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Suggest
{
    public function __construct(
        private readonly \Atoolo\Search\Suggest $suggest
    ) {
    }

    #[GQL\Query(name: 'suggest', type: 'SuggestResult!')]
    public function suggest(SuggestInput $input): SuggestResult
    {
        $factory = new SuggestQueryFactory();
        $query = $factory->create($input);
        return $this->suggest->suggest($query);
    }
}
