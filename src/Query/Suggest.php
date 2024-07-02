<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\Search\Dto\Search\Result\SuggestResult;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Suggest
{
    private readonly SuggestQueryFactory $factory;

    public function __construct(
        private readonly \Atoolo\Search\Suggest $suggest,
    ) {
        $this->factory = new SuggestQueryFactory();
    }

    #[GQL\Query(name: 'suggest', type: 'SuggestResult!')]
    public function suggest(SuggestInput $input): SuggestResult
    {
        $query = $this->factory->create($input);
        return $this->suggest->suggest($query);
    }
}
