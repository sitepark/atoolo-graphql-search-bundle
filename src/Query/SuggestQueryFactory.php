<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\Search\Dto\Search\Query\SuggestQuery;

class SuggestQueryFactory
{
    private readonly FilterListFactory $filterFactory;

    public function __construct()
    {
        $this->filterFactory = new FilterListFactory();
    }
    public function create(SuggestInput $input): SuggestQuery
    {
        $filterList = [];
        if (isset($input->filter)) {
            foreach ($this->filterFactory->create($input->filter) as $filter) {
                $filterList[] = $filter;
            }
        }

        return new SuggestQuery(
            $input->index,
            $input->text,
            $filterList,
            $input->limit
        );
    }
}
