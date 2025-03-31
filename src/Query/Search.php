<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\SearchInput;
use Atoolo\Rewrite\Service\UrlRewriteContext;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Exception;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;

#[GQL\Provider]
class Search
{
    private readonly SearchQueryFactory $factory;

    public function __construct(
        private readonly \Atoolo\Search\Search $search,
        private readonly UrlRewriteContext $urlRewriteContext,
    ) {
        $this->factory = new SearchQueryFactory();
    }

    #[GQL\Query(name: 'search', type: 'SearchResult!')]
    public function search(SearchInput $input): SearchResult
    {
        if ($input->urlBasePath !== null) {
            $this->urlRewriteContext->setBasePath($input->urlBasePath);
        }
        $query = $this->factory->create($input);
        try {
            return $this->search->search($query);
        } catch (Exception $e) {
            throw new UserError(
                $e->getMessage(),
                0,
                $e,
            );
        }
    }
}
