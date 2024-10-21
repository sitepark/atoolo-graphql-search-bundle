<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\Resource\Resource;
use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceHierarchyWalker;

class SymbolicImageFactory implements AssetFactory
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
        private readonly ResourceHierarchyLoader $hierarchyLoader,
    ) {}

    public function create(
        Resource $resource,
        ?string $variant = null,
    ): ?SymbolicImage {
        $walker = new ResourceHierarchyWalker($this->hierarchyLoader);
        $walker->init($resource);
        $currentResource = $resource;
        do {
            // FIXME: 'symbolicImage.content' may instead contain 'html' or 'key'
            $url = $currentResource->data->getString('base.symbolicImage.content.url');
            if (!empty($url)) {
                $rewrittenUrl = $this->urlRewriter->rewrite(
                    UrlRewriterType::IMAGE,
                    $url,
                );
                return new SymbolicImage($rewrittenUrl);
            }
        } while ($currentResource = $walker->primaryParent());
        return null;
    }
}
