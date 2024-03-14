<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use InvalidArgumentException;

class HierarchyResolver implements Resolver
{
    private const TYPE_NAVIGATION = 'navigation';

    private const TYPE_CATEGORY = 'category';

    public function __construct(
        private readonly ResourceHierarchyLoader $navigationLoader,
        private readonly ResourceHierarchyLoader $categoryHierarchyLoader
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getRoot(Hierarchy $hierarchy): Resource
    {
        $resource = $hierarchy->resource;
        return $this->getLoader($hierarchy->type)
            ->loadRoot($resource->getLocation());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getParent(Hierarchy $hierarchy): ?Resource
    {
        $resource = $hierarchy->resource;
        return $this->getLoader($hierarchy->type)
            ->loadParent($resource->getLocation());
    }

    /**
     * @return Resource[]
     * @throws InvalidArgumentException
     */
    public function getPath(Hierarchy $hierarchy): array
    {
        $resource = $hierarchy->resource;
        return $this->getLoader($hierarchy->type)
            ->loadPath($resource->getLocation());
    }

    /**
     * @return Resource[]
     * @throws InvalidArgumentException
     */
    public function getChildren(Hierarchy $hierarchy): array
    {
        $resource = $hierarchy->resource;
        return $this->getLoader($hierarchy->type)
            ->loadChildren($resource->getLocation());
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getLoader(string $type): ResourceHierarchyLoader
    {
        if ($type === self::TYPE_NAVIGATION) {
            return $this->navigationLoader;
        }
        if ($type === self::TYPE_CATEGORY) {
            return $this->categoryHierarchyLoader;
        }
        throw new InvalidArgumentException(
            'unknown tree type ' .
            '"' . $type . '"'
        );
    }
}
