<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\TeaserFeatureFactory;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceTeaserFeatureResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\TeaserFeature;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceTeaserFeatureResolver::class)]
class ResourceTeaserFeatureResolverTest extends TestCase
{
    private ResourceTeaserFeatureResolver $resolver;

    private TeaserFeatureFactory&MockObject $firstTeaserFeatureFactory;

    private TeaserFeatureFactory&MockObject $lastTeaserFeatureFactory;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->firstTeaserFeatureFactory = $this->createMock(
            TeaserFeatureFactory::class,
        );
        $this->lastTeaserFeatureFactory =  $this->createMock(
            TeaserFeatureFactory::class,
        );
        $this->resolver = new ResourceTeaserFeatureResolver(
            [
                $this->firstTeaserFeatureFactory,
                $this->lastTeaserFeatureFactory,
            ],
        );
    }

    public function testGetTeaserFeatures(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $teaserFeatureFirst = $this->createStub(TeaserFeature::class);
        $teaserFeatureOther = $this->createStub(TeaserFeature::class);
        $teaserFeatureLast = $this->createStub(TeaserFeature::class);
        $this->firstTeaserFeatureFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource)
            ->willReturn(
                [
                    $teaserFeatureFirst,
                    null,
                    $teaserFeatureOther,
                ],
            );
        $this->lastTeaserFeatureFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource)
            ->willReturn(
                [
                    null,
                    $teaserFeatureLast,
                    $teaserFeatureLast,
                ],
            );

        $result = $this->resolver->getTeaserFeatures($resource, $args);
        $this->assertSame(
            [
                $teaserFeatureFirst,
                $teaserFeatureOther,
                $teaserFeatureLast,
                $teaserFeatureLast,
            ],
            $result,
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
