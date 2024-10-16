<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\DelegatingTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceResolver::class)]
class ResourceResolverTest extends TestCase
{
    public function testGetTeaser(): void
    {
        $teaserFactory = $this->createStub(
            DelegatingTeaserFactory::class,
        );

        $teaser = $this->createStub(Teaser::class);
        $teaserFactory->method('create')
            ->willReturn($teaser);

        $resolver = new ResourceResolver($teaserFactory);

        $this->assertEquals(
            $teaser,
            $resolver->getTeaser($this->createStub(Resource::class)),
            'Should return the teaser resolved by the teaserFactory',
        );
    }

    public function testGetNavigation(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(DelegatingTeaserFactory::class),
        );

        $resource = $this->createStub(Resource::class);
        $hierarchy = $resolver->getNavigation($resource);

        $expected = new Hierarchy('navigation', $resource);
        $this->assertEquals(
            $expected,
            $hierarchy,
            'Should return a Hierarchy with the navigation ' .
            'type and the resource',
        );
    }

    public function testGetContentSectionTypes(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(DelegatingTeaserFactory::class),
        );

        $resource = TestResourceFactory::create([
            'contentSectionTypes' => ['type1'],
        ]);

        $this->assertEquals(
            ['type1'],
            $resolver->getContentSectionTypes($resource),
            'Should return the content section types from the resource',
        );
    }

    /**
     * @throws Exception
     */
    public function testGetExplain(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(DelegatingTeaserFactory::class),
        );

        $resource = TestResourceFactory::create([
            'explain' => [
                'score' => 5.0,
                'type' => 'score',
                'description' => 'test',
            ],
        ]);

        $this->assertEquals(
            [
                'score' => 5.0,
                'type' => 'score',
                'description' => 'test',
            ],
            $resolver->getExplain($resource),
            'Should return the explain data from the resource',
        );
    }

    /**
     * @throws Exception
     */
    public function testGetExplainWithoutExplainInResourceData(): void
    {
        $resolver = new ResourceResolver(
            $this->createStub(DelegatingTeaserFactory::class),
        );

        $resource = TestResourceFactory::create([]);

        $this->assertNull(
            $resolver->getExplain($resource),
            'Should return null if there is no explain data in the resource',
        );
    }
}
