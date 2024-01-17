<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Resolver\TeaserResolver;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

class MediaTeaserResolver implements TeaserResolver
{

    public function accept(Resource $resource): bool
    {
        return $this->isMedia($resource);
    }

    public function resolve(Resource $resource): Teaser
    {
        $teaser = new MediaTeaser();
        $teaser->url = $resource->getData()->getString('init.mediaUrl');
        $teaser->headline = $resource->getData()->getString(
            'base.teaser.headline',
            $resource->getName()
        );
        $teaser->text = $resource->getData()->getString('base.teaser.text');
        $teaser->contentType = $resource->getData()->getString('base.mime');
        $teaser->contentLength = $resource->getData()->getInt('base.filesize');
        return $teaser;
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
