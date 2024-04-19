<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceHierarchyLoader;

class ArticleTeaserFactory implements TeaserFactory
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
        private readonly ResourceHierarchyLoader $navigationLoader,
    ) {
    }

    public function create(Resource $resource): Teaser
    {
        $url = $this->urlRewriter->rewrite(
            UrlRewriterType::LINK,
            $resource->location
        );

        $headline = $resource->data->getString(
            'base.teaser.headline',
            $resource->name
        );
        $text = $resource->data->getString('base.teaser.text');
        $kicker = $this->lookupKicker($resource);

        return new ArticleTeaser(
            $url,
            $headline,
            $text,
            $kicker,
            null, // will be resolved by getAsset()
            $resource
        );
    }

    private function lookupKicker(Resource $resource): ?string
    {
        $kicker = $resource->data->getString(
            'base.teaser.kicker',
            $resource->data->getString('base.kicker')
        );
        if (!empty($kicker)) {
            return $kicker;
        }
        $primaryPath = $this->navigationLoader->loadPrimaryPath(
            $resource->toLocation()
        );
        for ($i = count($primaryPath) - 1; $i >= 1; $i--) {
            $kicker = $primaryPath[$i]->data->getString('base.kicker');
            if (!empty($kicker)) {
                return $kicker;
            }
        }
        return null;
    }
}
