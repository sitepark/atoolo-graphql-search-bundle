<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query\Context;

use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\Rewrite\Service\UrlRewriteContext;

class ContextDispatcher
{
    public function __construct(
        private readonly UrlRewriteContext $urlRewriteContext,
    ) {}

    public function dispatch(SearchContextInput $context): void
    {
        if ($context->urlBasePath !== null) {
            $this->urlRewriteContext->setBasePath($context->urlBasePath);
        }
        if ($context->resourceLocation !== null) {
            $this->urlRewriteContext->setResourceLocation($context->resourceLocation);
        }
        if ($context->options !== null) {
            if ($context->options->sameNavigation !== null) {
                $this->urlRewriteContext->setSameNavigation($context->options->sameNavigation);
            }
        }
    }
}
