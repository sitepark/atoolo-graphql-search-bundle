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
        private readonly ResourceHierarchyLoader $categoryHierarchyLoader,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function getRoot(Hierarchy $hierarchy): Resource
    {
        $resource = $hierarchy->root;
        return $this->getLoader($hierarchy->type)
            ->loadRoot($resource->toLocation());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getPrimaryParent(Hierarchy $hierarchy): ?Resource
    {
        $resource = $hierarchy->root;
        return $this->getLoader($hierarchy->type)
            ->loadPrimaryParent($resource->toLocation());
    }

    /**
     * @return Resource[]
     * @throws InvalidArgumentException
     */
    public function getPrimaryPath(Hierarchy $hierarchy): array
    {
        $resource = $hierarchy->root;
        return $this->getLoader($hierarchy->type)
            ->loadPrimaryPath($resource->toLocation());
    }

    /**
     * @return Resource[]
     * @throws InvalidArgumentException
     */
    public function getChildren(Hierarchy $hierarchy): array
    {
        $resource = $hierarchy->root;
        return $this->getLoader($hierarchy->type)
            ->loadChildren($resource->toLocation());
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
            '"' . $type . '"',
        );
    }
}
