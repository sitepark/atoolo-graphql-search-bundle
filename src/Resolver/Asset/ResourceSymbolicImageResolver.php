<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceHierarchyWalker;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ResourceSymbolicImageResolver implements ResourceAssetResolver
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
        private readonly ResourceHierarchyLoader $hierarchyLoader
    ) {
    }

    public function getAsset(
        Resource $resource,
        ArgumentInterface $args
    ): ?Asset {
        return $this->getSymbolicImage($resource, $args);
    }

    /**
     * Traverses the resources hierarchy from bottom
     * to top (including the passed resource) and returns
     * the first non-null symbolic image found in the
     * base data
     */
    public function getSymbolicImage(
        Resource $resource,
        ArgumentInterface $args
    ): ?SymbolicImage {
        $walker = new ResourceHierarchyWalker($this->hierarchyLoader);
        $walker->init($resource);
        $currentResource = $resource;
        do {
            $url = $currentResource->data->getString('base.symbolicImage.url');
            if (!empty($url)) {
                $rewrittenUrl = $this->urlRewriter->rewrite(
                    UrlRewriterType::IMAGE,
                    $url
                );
                return  new SymbolicImage($rewrittenUrl);
            }
        } while ($currentResource = $walker->primaryParent());
        return null;
    }
}
