<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceResolverContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceResolverContext::class)]
class ResourceResolverContextTest extends TestCase
{
    public function testSetAndGetResourceLocation(): void
    {
        $context = new ResourceResolverContext();
        $context->setResourceLocation('/example/location.php');

        $this->assertSame('/example/location.php', $context->getResourceLocation());
    }

    public function testSetAndIsSameNavigation(): void
    {
        $context = new ResourceResolverContext();
        $context->setSameNavigation(true);

        $this->assertTrue($context->isSameNavigation());
    }
}
