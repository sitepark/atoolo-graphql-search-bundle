<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\Link\ResourceLinkResolver;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class MediaTeaserFactory implements TeaserFactory
{
    public function __construct(
        private readonly ResourceLinkResolver $resourceLinkResolver,
    ) {}

    public function create(Resource $resource): Teaser
    {
        if (!$this->isMedia($resource)) {
            throw new \InvalidArgumentException('Resource is not a media: ' .
                $resource->location . ', ' .
                'objectType: ' . $resource->objectType);
        }

        $link = $this->resourceLinkResolver->getLink(
            $resource,
        );

        $headline = $resource->data->getString(
            'base.teaser.headline',
            $resource->name,
        );
        $text = $resource->data->getString('base.teaser.text');
        $contentType = $resource->data->getString('base.mime');
        $contentLength = $resource->data->getInt('base.filesize');

        return new MediaTeaser(
            $link,
            $headline,
            $text === '' ? null : $text,
            $resource,
        );
    }

    private function isMedia(Resource $resource): bool
    {
        if ($resource->objectType === 'media') {
            return true;
        }
        if ($resource->objectType === 'embedded-media') {
            return true;
        }
        return false;
    }
}
