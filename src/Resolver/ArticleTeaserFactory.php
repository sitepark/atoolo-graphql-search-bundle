<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class ArticleTeaserFactory implements TeaserFactory
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter
    ) {
    }

    public function create(Resource $resource): Teaser
    {
        $url = $this->urlRewriter->rewrite(
            UrlRewriterType::LINK,
            $resource->getLocation()
        );

        $headline = $resource->getData()->getString(
            'base.teaser.headline',
            $resource->getName()
        );
        $text = $resource->getData()->getString('base.teaser.text');

        return new ArticleTeaser(
            $url,
            $headline,
            $text,
            null, // will be resolved by getAsset()
            $resource
        );
    }
}
