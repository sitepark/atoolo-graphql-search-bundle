<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\ClassNameTypeResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassNameTypeResolver::class)]
class ClassNameTypeResolverTest extends TestCase
{
    public function testResolveType(): void
    {
        $resolver = new ClassNameTypeResolver();
        $this->assertEquals(
            'ClassNameTypeResolverDummyClass',
            $resolver->resolveType(new ClassNameTypeResolverDummyClass())
        );
    }
}
