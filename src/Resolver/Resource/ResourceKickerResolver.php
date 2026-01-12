<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceHierarchyWalker;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Resource\ResourceLoader;
use Atoolo\Resource\ResourceLocation;

class ResourceKickerResolver implements Resolver
{
    public function __construct(
        private readonly ResourceHierarchyLoader $hierarchyLoader,
        private readonly ResourceResolverContext $resourceResolverContext,
        private readonly ResourceLoader $resourceLoader,
    ) {}

    public function getKicker(
        Resource $resource,
    ): ?string {

        $kickerText = $this->getDirectKicker($resource);
        if ($kickerText !== null) {
            return $kickerText;
        }

        $changedNavigationParent = $this->getChangedNavigationParentResource($resource->lang);
        if ($changedNavigationParent !== null) {
            $kickerText = $this->getDirectKicker($changedNavigationParent);
            if ($kickerText !== null) {
                return $kickerText;
            }
        }

        return $this->lookupKicker($changedNavigationParent ?? $resource);
    }

    private function lookupKicker(Resource $resource): ?string
    {

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

    private function getChangedNavigationParentResource(ResourceLanguage $lang): ?Resource
    {
        if (!$this->resourceResolverContext->isSameNavigation()) {
            return null;
        }
        $foreignResourceLocation = $this->resourceResolverContext->getResourceLocation();
        if ($foreignResourceLocation === null) {
            return null;
        }

        return $this->resourceLoader->load(ResourceLocation::of($foreignResourceLocation, $lang));
    }

    private function getDirectKicker(
        Resource $resource,
    ): ?string {
        $kickerText = $resource->data->getString(
            'base.teaser.kicker',
            $resource->data->getString('base.kicker'),
        );
        return !empty($kickerText) ? $kickerText : null;
    }
}
