<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class ResourceResolver implements Resolver
{
    public function __construct(
        private readonly ResourceToTeaserResolver $resourceToTeaserResolver
    ) {
    }

    public function getTeaser(Resource $resource): Teaser
    {
        return $this->resourceToTeaserResolver->resolve($resource);
    }

    public function getNavigation(Resource $resource): Hierarchy
    {
        return new Hierarchy('navigation', $resource);
    }

    /**
     * @return string[]|null
     */
    public function getContentSectionTypes(Resource $resource): ?array
    {
        return $resource->getData('init.contentSectionTypes');
    }
}
