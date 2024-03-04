<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use ReflectionMethod;

/**
 * A method of a resolver determined by the {@link ResolverMapRegistry}
 * @codeCoverageIgnore
 */
class ResolverMethod
{
    public function __construct(
        public readonly Resolver $resolver,
        public readonly string $fieldName,
        public readonly ReflectionMethod $reflectionMethod
    ) {
    }
}
