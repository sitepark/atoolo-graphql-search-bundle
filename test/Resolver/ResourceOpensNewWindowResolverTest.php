<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ResourceOpensNewWindowResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceOpensNewWindowResolver::class)]
class ResourceOpensNewWindowResolverTest extends TestCase
{
    private ResourceOpensNewWindowResolver $resolver;

    public function setUp(): void
    {
        $this->resolver = new ResourceOpensNewWindowResolver();
    }

    public function testGetOpensNewWindowTrue(): void
    {
        $resource = $this->createResource([
            'external' => [
                'newWindow' => true,
            ],
        ]);
        $this->assertTrue(
            $this->resolver->getOpensNewWindow($resource),
            'true expected since external.newWindow is set to true',
        );
    }

    public function testGetOpensNewWindowFalse(): void
    {
        $resource = $this->createResource([
            'external' => [
                'newWindow' => false,
            ],
        ]);
        $this->assertFalse(
            $this->resolver->getOpensNewWindow($resource),
            'false expected since external.newWindow is set to false',
        );
    }

    public function testGetOpensNewWindowUnset(): void
    {
        $resource = $this->createResource([]);
        $this->assertFalse(
            $this->resolver->getOpensNewWindow($resource),
            'false expected since external.newWindow is unset',
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
