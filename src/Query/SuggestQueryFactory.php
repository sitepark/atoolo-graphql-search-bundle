<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\Search\Dto\Search\Query\SuggestQuery;

class SuggestQueryFactory
{
    public function create(SuggestInput $input): SuggestQuery
    {
        $filterList = [];
        if (isset($input->filter)) {
            $factory = new FilterListFactory();
            foreach ($factory->create($input->filter) as $filter) {
                $filterList[] = $filter;
            }
        }

        return new SuggestQuery(
            $input->index,
            $input->text,
            $filterList,
            $input->limit ?? 10
        );
    }
}
