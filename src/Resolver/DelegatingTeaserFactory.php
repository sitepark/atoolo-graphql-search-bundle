<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class DelegatingTeaserFactory implements TeaserFactory
{
    /**
     * @param iterable<TeaserFactory> $factoryList
     */
    public function __construct(
        private readonly iterable $factoryList
    ) {
    }

    public function accept(Resource $resource): bool
    {
        foreach ($this->factoryList as $resolver) {
            if (!$resolver->accept($resource)) {
                return false;
            }
        }
        return true;
    }

    public function create(Resource $resource): Teaser
    {
        foreach ($this->factoryList as $resolver) {
            if (!$resolver->accept($resource)) {
                continue;
            }
            return $resolver->create($resource);
        }

        throw new \InvalidArgumentException(
            'No factory found for ' . $resource->getLocation()
        );
    }
}
