<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;

class HierarchyResolver implements Resolver
{
    public function __construct(
        private readonly ResourceHierarchyLoader $navigationLoader,
        private readonly ResourceHierarchyLoader $categoryHierarchyLoader
    ) {
    }

    public function getRoot(Hierarchy $hierarchy): Resource
    {
        $resource = $hierarchy->getResource();
        return $this->getLoader($hierarchy->getType())
            ->loadRoot($resource->getLocation());
    }

    public function getParent(Hierarchy $hierarchy): ?Resource
    {
        $resource = $hierarchy->getResource();
        return $this->getLoader($hierarchy->getType())
            ->loadParent($resource->getLocation());
    }

    /**
     * @return Resource[]
     */
    public function getPath(Hierarchy $hierarchy): array
    {
        $resource = $hierarchy->getResource();
        return $this->getLoader($hierarchy->getType())
            ->loadPath($resource->getLocation());
    }

    /**
     * @return Resource[]
     */
    public function getChildren(Hierarchy $hierarchy): array
    {
        $resource = $hierarchy->getResource();
        return $this->getLoader($hierarchy->getType())
            ->loadChildren($resource->getLocation());
    }

    private function getLoader(string $type): ResourceHierarchyLoader
    {
        if ($type === 'navigation') {
            return $this->navigationLoader;
        }
        if ($type === 'category') {
            return $this->categoryHierarchyLoader;
        }
        throw new \InvalidArgumentException(
            'unknown tree type ' .
            '"' . $type . '"'
        );
    }
}