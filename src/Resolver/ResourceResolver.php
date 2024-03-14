<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class ResourceResolver implements Resolver
{
    public function __construct(
        private readonly TeaserFactory $teaserFactory
    ) {
    }

    public function getTeaser(Resource $resource): Teaser
    {
        return $this->teaserFactory->resolve($resource);
    }

    public function getNavigation(Resource $resource): Hierarchy
    {
        return new Hierarchy('navigation', $resource);
    }

    /**
     * @return string[]
     */
    public function getContentSectionTypes(Resource $resource): array
    {
        /** @var string[] $contentSectionTypes */
        $contentSectionTypes = $resource->getData()->getArray(
            'init.contentSectionTypes'
        );
        return $contentSectionTypes;
    }
}
