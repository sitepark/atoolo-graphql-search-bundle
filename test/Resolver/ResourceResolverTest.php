<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\DelegatingTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\ResourceResolver;
use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceResolver::class)]
class ResourceResolverTest extends TestCase
{
    public function testGetTeaser(): void
    {
        $teaserFactory = $this->createStub(
            DelegatingTeaserFactory::class
        );

        $teaser = $this->createStub(Teaser::class);
        $teaserFactory->method('create')
            ->willReturn($teaser);

        $resolver = new ResourceResolver($teaserFactory);

        $this->assertEquals(
            $teaser,
            $resolver->getTeaser($this->createStub(Resource::class)),
            'Should return the teaser resolved by the teaserFactory'
        );
    }

    public function testGetNavigation(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(DelegatingTeaserFactory::class)
        );

        $resource = $this->createStub(Resource::class);
        $hierarchy = $resolver->getNavigation($resource);

        $expected = new Hierarchy('navigation', $resource);
        $this->assertEquals(
            $expected,
            $hierarchy,
            'Should return a Hierarchy with the navigation ' .
            'type and the resource'
        );
    }

    public function testGetContentSectionTypes(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(DelegatingTeaserFactory::class)
        );

        $resource = new Resource(
            '',
            '',
            '',
            '',
            '',
            ['init' => ['contentSectionTypes' => ['type1']]]
        );

        $this->assertEquals(
            ['type1'],
            $resolver->getContentSectionTypes($resource),
            'Should return the content section types from the resource'
        );
    }
}
