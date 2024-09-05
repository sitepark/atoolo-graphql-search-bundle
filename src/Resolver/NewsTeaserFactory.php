<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\Link\ResourceLinkResolver;
use Atoolo\GraphQL\Search\Types\NewsTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class NewsTeaserFactory implements TeaserFactory
{
    public function __construct(
        private readonly ResourceLinkResolver $resourceLinkResolver,
    ) {}

    public function create(Resource $resource): Teaser
    {
        $link = $this->resourceLinkResolver->getLink(
            $resource,
        );

        $headline = $resource->data->getString(
            'base.teaser.headline',
            $resource->name,
        );
        $text = $resource->data->getString('base.teaser.text');

        return new NewsTeaser(
            $link,
            $headline,
            $text === '' ? null : $text,
            $resource,
        );
    }
}
