<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SuggestInput;
use Atoolo\Resource\ResourceLanguage;
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
            $input->text,
            ResourceLanguage::of($input->lang),
            $filterList,
            $input->limit ?? 10,
            $input->archive ?? false,
        );
    }
}
