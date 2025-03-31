<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Atoolo\GraphQL\Search\Input\MoreLikeThisInput;
use Atoolo\Rewrite\Service\UrlRewriteContext;
use Atoolo\Search\Dto\Search\Result\SearchResult;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class MoreLikeThis
{
    private readonly MoreLikeThisQueryFactory $factory;

    public function __construct(
        private readonly \Atoolo\Search\MoreLikeThis $moreLikeThis,
        private readonly UrlRewriteContext $urlRewriteContext,
    ) {
        $this->factory = new MoreLikeThisQueryFactory();
    }

    #[GQL\Query(name: 'moreLikeThis', type: 'SearchResult!')]
    public function moreLikeThis(MoreLikeThisInput $input): SearchResult
    {
        if ($input->urlBasePath !== null) {
            $this->urlRewriteContext->setBasePath($input->urlBasePath);
        }
        $query = $this->factory->create($input);
        return $this->moreLikeThis->moreLikeThis($query);
    }
}
