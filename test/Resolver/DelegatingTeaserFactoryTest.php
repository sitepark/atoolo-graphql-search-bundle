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
class DelegatingTeaserFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getObjectType')
            ->willReturn('myObjectType');

        $teaser = $this->createStub(Teaser::class);
        $teaserFactory = $this->createStub(TeaserFactory::class);
        $fallbackFactory = $this->createStub(TeaserFactory::class);
        $teaserFactory->method('create')
            ->willReturn($teaser);
        $resolver = new DelegatingTeaserFactory(
            [
                'myObjectType' => $teaserFactory
            ],
            $fallbackFactory
        );

        $this->assertEquals(
            $teaser,
            $resolver->create($resource),
            'Should return the teaser resolved by the myObjectType resolver'
        );
    }

    public function testCreateWithTraversable(): void
    {
        $resource = $this->createStub(Resource::class);
        $resource->method('getObjectType')
            ->willReturn('myObjectType');

        $teaser = $this->createStub(Teaser::class);
        $teaserFactory = $this->createStub(TeaserFactory::class);
        $fallbackFactory = $this->createStub(TeaserFactory::class);
        $teaserFactory->method('create')
            ->willReturn($teaser);
        $resolver = new DelegatingTeaserFactory(
            new TeaserFactoryTestIteratorAggregate([
                'myObjectType' => $teaserFactory
            ]),
            $fallbackFactory
        );

        $this->assertEquals(
            $teaser,
            $resolver->create($resource),
            'Should return the teaser resolved by the myObjectType resolver'
        );
    }

    public function testCreateWithFallbackFactory(): void
    {
        $teaser = $this->createStub(Teaser::class);
        $fallbackFactory = $this->createStub(TeaserFactory::class);
        $fallbackFactory->method('create')
            ->willReturn($teaser);

        $resolver = new DelegatingTeaserFactory([], $fallbackFactory);

        $this->assertEquals(
            $teaser,
            $resolver->create($this->createStub(Resource::class)),
            'Should return the teaser by the fallback resolver'
        );
    }
}
