<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceHierarchyWalker;

class ResourceKickerResolver
{
    public function __construct(
        private readonly ResourceHierarchyLoader $hierarchyLoader
    ) {
    }

    public function getKicker(
        Resource $resource,
    ): ?string {
        $kickerText = $resource->data->getString(
            'base.teaser.kicker',
            $resource->data->getString('base.kicker')
        );
        if (!empty($kickerText)) {
            return $kickerText;
        }
        $walker = new ResourceHierarchyWalker($this->hierarchyLoader);
        $walker->init($resource);
        while ($parent = $walker->primaryParent()) {
            $kickerText = $parent->data->getString('base.kicker');
            if (!empty($kickerText)) {
                return $kickerText;
            }
        }
        return null;
    }
}
