<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query\Context;

use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceResolverContext;
use Atoolo\Rewrite\Service\UrlRewriteContext;

class ContextDispatcher
{
    public function __construct(
        private readonly UrlRewriteContext $urlRewriteContext,
        private readonly ResourceResolverContext $resourceResolverContext,
    ) {}

    public function dispatch(SearchContextInput $context): void
    {
        if ($context->urlBasePath !== null) {
            $this->urlRewriteContext->setBasePath($context->urlBasePath);
        }
        if ($context->resourceLocation !== null) {
            $this->urlRewriteContext->setResourceLocation($context->resourceLocation);
            $this->resourceResolverContext->setResourceLocation($context->resourceLocation);
        }
        if (($context->options !== null) && $context->options->sameNavigation !== null) {
            $this->urlRewriteContext->setSameNavigation($context->options->sameNavigation);
            $this->resourceResolverContext->setSameNavigation($context->options->sameNavigation);
        }
    }
}
