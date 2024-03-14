<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class TeaserFactory
{
    /**
     * @param iterable<TeaserResolver> $resolverList
     */
    public function __construct(
        private readonly iterable $resolverList
    ) {
    }

    public function resolve(Resource $resource): Teaser
    {
        foreach ($this->resolverList as $resolver) {
            if (!$resolver->accept($resource)) {
                continue;
            }
            return $resolver->resolve($resource);
        }

        throw new \InvalidArgumentException(
            'Unresolvable resource ' . $resource->getLocation()
        );
    }
}
