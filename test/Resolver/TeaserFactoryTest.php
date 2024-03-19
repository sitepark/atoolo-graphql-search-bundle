<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\DelegatingTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\TeaserFactory;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DelegatingTeaserFactory::class)]
class TeaserFactoryTest extends TestCase
{
    public function testResolve(): void
    {
        $teaser = $this->createStub(Teaser::class);
        $teaserResolver = $this->createStub(TeaserFactory::class);
        $teaserResolver->method('accept')
            ->willReturn(true);
        $teaserResolver->method('resolve')
            ->willReturn($teaser);
        $resolver = new DelegatingTeaserFactory([$teaserResolver]);

        $this->assertEquals(
            $teaser,
            $resolver->create($this->createStub(Resource::class)),
            'Should return the teaser resolved by the first resolver'
        );
    }

    public function testWithUnacceptedResource(): void
    {
        $teaserResolver = $this->createStub(TeaserFactory::class);
        $teaserResolver->method('accept')
            ->willReturn(false);
        $resolver = new DelegatingTeaserFactory([$teaserResolver]);

        $this->expectException(\InvalidArgumentException::class);
        $resolver->create($this->createStub(Resource::class));
    }
}
