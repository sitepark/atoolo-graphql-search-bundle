<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ResourceToTeaserResolver;
use Atoolo\GraphQL\Search\Resolver\TeaserResolver;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceToTeaserResolver::class)]
class ResourceToTeaserResolverTest extends TestCase
{
    public function testResolve(): void
    {
        $teaser = $this->createStub(Teaser::class);
        $teaserResolver = $this->createStub(TeaserResolver::class);
        $teaserResolver->method('accept')
            ->willReturn(true);
        $teaserResolver->method('resolve')
            ->willReturn($teaser);
        $resolver = new ResourceToTeaserResolver([$teaserResolver]);

        $this->assertEquals(
            $teaser,
            $resolver->resolve($this->createStub(Resource::class)),
            'Should return the teaser resolved by the first resolver'
        );
    }

    public function testWithUnacceptedResource(): void
    {
        $teaserResolver = $this->createStub(TeaserResolver::class);
        $teaserResolver->method('accept')
            ->willReturn(false);
        $resolver = new ResourceToTeaserResolver([$teaserResolver]);

        $this->expectException(\InvalidArgumentException::class);
        $resolver->resolve($this->createStub(Resource::class));
    }
}
