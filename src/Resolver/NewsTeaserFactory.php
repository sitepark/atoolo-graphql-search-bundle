<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\NewsTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class NewsTeaserFactory implements TeaserFactory
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter
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

        return new NewsTeaser(
            $url,
            $headline,
            $text,
            $resource
        );
    }
}
