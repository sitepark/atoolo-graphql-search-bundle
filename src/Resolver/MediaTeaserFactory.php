<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class MediaTeaserFactory implements TeaserFactory
{
    public function __construct(private readonly UrlRewriter $urlRewriter)
    {
    }

    public function create(Resource $resource): Teaser
    {
        if (!$this->isMedia($resource)) {
            throw new \InvalidArgumentException('Resource is not a media: ' .
                $resource->getLocation() . ', ' .
                'objectType: ' . $resource->getObjectType());
        }

        $url = $this->urlRewriter->rewrite(
            UrlRewriterType::MEDIA,
            $resource->getData()->getString('init.mediaUrl')
        );
        $headline = $resource->getData()->getString(
            'base.teaser.headline',
            $resource->getName()
        );
        $text = $resource->getData()->getString('base.teaser.text');
        $contentType = $resource->getData()->getString('base.mime');
        $contentLength = $resource->getData()->getInt('base.filesize');

        return new MediaTeaser(
            $url,
            $headline,
            $text,
            $contentType,
            $contentLength,
            null,
            $resource
        );
    }

    private function isMedia(Resource $resource): bool
    {
        if ($resource->getObjectType() === 'media') {
            return true;
        }
        if ($resource->getObjectType() === 'embedded-media') {
            return true;
        }
        return false;
    }
}
