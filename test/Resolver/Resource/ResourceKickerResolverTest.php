<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceKickerResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceResolverContext;
use Atoolo\Resource\Loader\SiteKitNavigationHierarchyLoader;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceKickerResolver::class)]
class ResourceKickerResolverTest extends TestCase
{
    private ResourceKickerResolver $resolver;

    private SiteKitNavigationHierarchyLoader $hierarchyLoader;

    private ResourceResolverContext&MockObject $resourceResolverContext;

    private ResourceLoader $resourceLoader;

    public function setUp(): void
    {
        $resourceBaseDir = realpath(
            __DIR__ . '/../../resources/' .
                'ArticleTeaserResolver',
        );
        $resourceLoader = $this->createStub(
            ResourceLoader::class,
        );
        $resourceLoader->method('load')
            ->willReturnCallback(static function ($location) use (
                $resourceBaseDir
            ) {
                $resource =  include $resourceBaseDir . $location->location;
                $error = error_get_last();
                return $resource;
            });

        $this->resourceResolverContext = $this->createMock(ResourceResolverContext::class);

        $this->hierarchyLoader = new SiteKitNavigationHierarchyLoader(
            $resourceLoader,
        );

        $this->resolver = new ResourceKickerResolver(
            $this->hierarchyLoader,
            $this->resourceResolverContext,
            $resourceLoader,
        );
    }

    public function testGetKickerInTeaserData(): void
    {
        $resource = Resource::create([
            'base' => [
                'teaser' => [
                    'kicker' => 'Teaser-Kicker',
                ],
                'kicker' => 'Base-Kicker',
            ],
        ]);
        $this->assertEquals(
            'Teaser-Kicker',
            $this->resolver->getKicker($resource),
            'unexpected teaser kicker',
        );
    }

    public function testGetKickerInBaseData(): void
    {
        $teaser = Resource::create([
            'base' => [
                'kicker' => 'Base-Kicker',
            ],
        ]);
        $this->assertEquals(
            'Base-Kicker',
            $this->resolver->getKicker($teaser),
            'unexpected teaser kicker',
        );
    }

    public function testGetKickerInherited(): void
    {
        $teaser = Resource::create([
            'base' => [
                'trees' => [
                    'navigation' => [
                        'parents' => [
                            'parent' => [
                                'id' => 'parent',
                                'url' => '/parent.php',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $this->assertEquals(
            'Parent-Kicker',
            $this->resolver->getKicker($teaser),
            'unexpected teaser kicker',
        );
    }

    public function testGetKickerNotFound(): void
    {
        $teaser = Resource::create([]);
        $this->assertNull(
            $this->resolver->getKicker($teaser),
            'kicker should be null',
        );
    }

    public function testGetKickerWithSameNavigation(): void
    {
        $teaser = Resource::create([
            'base' => [
                'trees' => [
                    'navigation' => [
                        'parents' => [
                            'parent' => [
                                'id' => 'parent',
                                'url' => '/parent.php',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->resourceResolverContext->method('isSameNavigation')
            ->willReturn(true);

        $this->resourceResolverContext->method('getResourceLocation')
            ->willReturn('/changed-navigation-parent.php');

        $this->assertEquals(
            'Changed-Navigation-Parent-Kicker',
            $this->resolver->getKicker($teaser),
            'unexpected teaser kicker',
        );
    }

    public function testGetKickerWithSameNavigationButNullLocation(): void
    {
        $teaser = Resource::create([
            'base' => [
                'trees' => [
                    'navigation' => [
                        'parents' => [
                            'parent' => [
                                'id' => 'parent',
                                'url' => '/parent.php',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->resourceResolverContext->method('isSameNavigation')
            ->willReturn(true);

        $this->resourceResolverContext->method('getResourceLocation')
            ->willReturn(null);

        $this->assertEquals(
            'Parent-Kicker',
            $this->resolver->getKicker($teaser),
            'unexpected teaser kicker',
        );
    }
}
