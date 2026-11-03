<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Exception\RootMissingException;
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

        $teaserKicker = $resource->data->getString('base.teaser.kicker');
        if ($teaserKicker !== '') {
            return $teaserKicker;
        }

        // A resource of another site is introduced by the kicker of that site's root.
        $foreignRoot = $this->getForeignSiteRoot($resource);
        if ($foreignRoot !== null) {
            return $this->getDirectKicker($foreignRoot);
        }

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

    /**
     * Returns the navigation root of the resource if it differs from the
     * navigation root of the resource in the context (e.g. a page of the
     * main site listed in a microsite). Not for `sameNavigation`, where the
     * resource is shown as part of the context's navigation.
     */
    private function getForeignSiteRoot(Resource $resource): ?Resource
    {
        if ($this->resourceResolverContext->isSameNavigation()) {
            return null;
        }
        $contextLocation = $this->resourceResolverContext->getResourceLocation();
        if ($contextLocation === null) {
            return null;
        }
        try {
            $contextRoot = $this->hierarchyLoader->loadRoot(
                ResourceLocation::of($contextLocation, $resource->lang),
            );
            $root = $this->hierarchyLoader->loadRoot($resource->toLocation());
        } catch (ResourceNotFoundException|InvalidResourceException|RootMissingException) {
            return null;
        }
        return $root->location !== $contextRoot->location ? $root : null;
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
