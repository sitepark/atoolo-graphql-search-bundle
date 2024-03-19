<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class DelegatingTeaserFactory implements TeaserFactory
{
    /**
     * @var array<string, TeaserFactory>
     */
    private readonly array $factories;

    /**
     * @param iterable<string, TeaserFactory> $factories
     */
    public function __construct(
        iterable $factories,
        private readonly TeaserFactory $fallbackFactory
    ) {
        $this->factories = $factories instanceof \Traversable ?
            iterator_to_array($factories) :
            $factories;
    }

    public function create(Resource $resource): Teaser
    {
        $objectType = $resource->getObjectType();
        if (!isset($this->factories[$objectType])) {
            return $this->fallbackFactory->create($resource);
        }
        return $this->factories[$objectType]->create($resource);
    }
}
