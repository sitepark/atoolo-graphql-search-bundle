<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query\Context;

use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ContextDispatcher::class)]
class ContextDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $context = new SearchContextInput();
        $context->urlBasePath = '/test';

        $urlRewriteContext = $this->createMock(\Atoolo\Rewrite\Service\UrlRewriteContext::class);
        $urlRewriteContext->expects($this->once())
            ->method('setBasePath')
            ->with('/test');

        $dispatcher = new ContextDispatcher($urlRewriteContext);
        $dispatcher->dispatch($context);
    }
}
