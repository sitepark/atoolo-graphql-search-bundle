<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\Resource\Loader\SiteKitNavigationHierarchyLoader;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceKickerResolver::class)]
class ResourceKickerResolverTest extends TestCase
{
    private ResourceKickerResolver $resolver;

    private SiteKitNavigationHierarchyLoader $hierarchyLoader;

    public function setUp(): void
    {
        $resourceBaseDir = realpath(
            __DIR__ . '/../resources/' .
            'ArticleTeaserResolver'
        );
        $resourceLoader = $this->createStub(
            ResourceLoader::class
        );
        $resourceLoader->method('load')
            ->willReturnCallback(static function ($location) use (
                $resourceBaseDir
            ) {
                $resource =  include $resourceBaseDir . $location->location;
                $error = error_get_last();
                return $resource;
            });

        $this->hierarchyLoader = new SiteKitNavigationHierarchyLoader(
            $resourceLoader
        );
        $this->resolver = new ResourceKickerResolver(
            $this->hierarchyLoader,
        );
    }

    public function testGetKickerInTeaserData(): void
    {
        $resource = $this->createResource([
            'base' => [
                'teaser' => [
                    'kicker' => 'Teaser-Kicker'
                ],
                'kicker' => 'Base-Kicker'
            ]
        ]);
        $this->assertEquals(
            'Teaser-Kicker',
            $this->resolver->getKicker($resource),
            'unexpected teaser kicker'
        );
    }

    public function testGetKickerInBaseData(): void
    {
        $teaser = $this->createResource([
            'base' => [
                'kicker' => 'Base-Kicker'
            ]
        ]);
        $this->assertEquals(
            'Base-Kicker',
            $this->resolver->getKicker($teaser),
            'unexpected teaser kicker'
        );
    }

    public function testGetKickerInherited(): void
    {
        $teaser = $this->createResource([
            'base' => [
                'trees' => [
                    'navigation' => [
                        'parents' => [
                            'parent' => [
                                'id' => 'parent',
                                'url' => '/parent.php'
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals(
            'Parent-Kicker',
            $this->resolver->getKicker($teaser),
            'unexpected teaser kicker'
        );
    }

    public function testGetKickerNotFound(): void
    {
        $teaser = $this->createResource([
        ]);
        $this->assertNull(
            $this->resolver->getKicker($teaser),
            'kicker should be null'
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
