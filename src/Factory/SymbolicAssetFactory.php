<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\Svg;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;
use Atoolo\Resource\ResourceHierarchyWalker;
use Atoolo\Rewrite\Dto\UrlRewriteOptions;
use Atoolo\Rewrite\Dto\UrlRewriteType;
use Atoolo\Rewrite\Service\UrlRewriter;

class SymbolicAssetFactory implements AssetFactory
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
        private readonly ResourceHierarchyLoader $hierarchyLoader,
    ) {}

    public function create(
        Resource $resource,
        ?string $variant = null,
    ): ?Asset {
        $walker = new ResourceHierarchyWalker($this->hierarchyLoader);
        $walker->init($resource);
        $currentResource = $resource;
        do {
            // FIXME: 'symbolicAsset.content' may instead contain 'html' or 'key'
            $url = $currentResource->data->getString('base.symbolicAsset.content.url');
            if (!empty($url)) {
                $rewrittenUrl = $this->urlRewriter->rewrite(
                    UrlRewriteType::IMAGE,
                    $url,
                    UrlRewriteOptions::builder()->lang($resource->lang->code)->build(),
                );
                return new Svg($rewrittenUrl);
            }
        } while ($currentResource = $walker->primaryParent());
        return null;
    }
}
