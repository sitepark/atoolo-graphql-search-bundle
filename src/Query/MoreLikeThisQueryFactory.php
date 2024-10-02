<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Search\Dto\Search\Query\MoreLikeThisQuery;

class MoreLikeThisQueryFactory
{
    private readonly FilterListFactory $filterFactory;

    public function __construct()
    {
        $this->filterFactory = new FilterListFactory();
    }
    public function create(MoreLikeThisInput $input): MoreLikeThisQuery
    {
        $filterList = [];
        if (isset($input->filter)) {
            foreach ($this->filterFactory->create($input->filter) as $filter) {
                $filterList[] = $filter;
            }
        }

        return new MoreLikeThisQuery(
            id: $input->id,
            lang: ResourceLanguage::of($input->lang),
            limit: $input->limit ?? 5,
            filter: $filterList,
        );
    }
}
