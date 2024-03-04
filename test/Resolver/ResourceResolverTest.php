<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ResourceResolver;
use Atoolo\GraphQL\Search\Resolver\ResourceToTeaserResolver;
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
        $resourceToTeaserResolver = $this->createStub(
            ResourceToTeaserResolver::class
        );

        $teaser = $this->createStub(Teaser::class);
        $resourceToTeaserResolver->method('resolve')
            ->willReturn($teaser);

        $resolver = new ResourceResolver($resourceToTeaserResolver);

        $this->assertEquals(
            $teaser,
            $resolver->getTeaser($this->createStub(Resource::class)),
            'Should return the teaser resolved by the resourceToTeaserResolver'
        );
    }

    public function testGetNavigation(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(ResourceToTeaserResolver::class)
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
            $this->createStub(ResourceToTeaserResolver::class)
        );

        $resource = new Resource(
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
