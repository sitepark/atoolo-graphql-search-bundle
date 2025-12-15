<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query\Context;

use Atoolo\GraphQL\Search\Input\SearchContextInput;
use Atoolo\GraphQL\Search\Input\SearchContextOptionsInput;
use Atoolo\GraphQL\Search\Query\Context\ContextDispatcher;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceResolverContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ContextDispatcher::class)]
class ContextDispatcherTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDispatch(): void
    {
        $context = new SearchContextInput();
        $context->urlBasePath = '/test';
        $context->resourceLocation = '/test.php';

        $urlRewriteContext = $this->createMock(\Atoolo\Rewrite\Service\UrlRewriteContext::class);
        $urlRewriteContext->expects($this->once())
            ->method('setBasePath')
            ->with('/test');
        $urlRewriteContext->expects($this->once())
            ->method('setResourceLocation')
            ->with('/test.php');

        $resourceResolverContext = $this->createMock(ResourceResolverContext::class);
        $resourceResolverContext->expects($this->once())
            ->method('setResourceLocation')
            ->with('/test.php');


        $dispatcher = new ContextDispatcher($urlRewriteContext, $resourceResolverContext);
        $dispatcher->dispatch($context);
    }

    /**
     * @throws Exception
     */
    public function testDispatchWithNullResourceLocation(): void
    {
        $context = new SearchContextInput();
        $context->urlBasePath = '/test';

        $urlRewriteContext = $this->createMock(\Atoolo\Rewrite\Service\UrlRewriteContext::class);
        $urlRewriteContext->expects($this->once())
            ->method('setBasePath')
            ->with('/test');

        $resourceResolverContext = $this->createMock(ResourceResolverContext::class);

        $dispatcher = new ContextDispatcher($urlRewriteContext, $resourceResolverContext);
        $dispatcher->dispatch($context);
    }

    public function testDispatchSameNavigation(): void
    {
        $context = new SearchContextInput();
        $option = new SearchContextOptionsInput();
        $option->sameNavigation = true;
        $context->options = $option;

        $urlRewriteContext = $this->createMock(\Atoolo\Rewrite\Service\UrlRewriteContext::class);
        $urlRewriteContext->expects($this->once())
            ->method('setSameNavigation')
            ->with(true);

        $resourceResolverContext = $this->createMock(ResourceResolverContext::class);
        $resourceResolverContext->expects($this->once())
            ->method('setSameNavigation')
            ->with(true);

        $dispatcher = new ContextDispatcher($urlRewriteContext, $resourceResolverContext);
        $dispatcher->dispatch($context);
    }
}
