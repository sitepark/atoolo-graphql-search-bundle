<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ResourceToTeaserResolver
{
    public function resolve(Resource $resource): Teaser
    {
        if ($this->isMedia($resource)) {
            return $this->createMediaTeaser($resource);
        }
        return $this->createArticleTeaser($resource);
    }

    private function createArticleTeaser(Resource $resource): ArticleTeaser
    {
        $teaser = new ArticleTeaser();
        $teaser->url = $resource->getLocation();
        $teaser->headline = $resource->getData('base.teaser.headline')
            ?? $resource->getName();
        $teaser->text = $resource->getData('base.teaser.text');
        $teaser->resource = $resource;
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

    private function createMediaTeaser(Resource $resource): MediaTeaser
    {
        $teaser = new MediaTeaser();
        $teaser->url = $resource->getData('init.mediaUrl');
        $teaser->headline = $resource->getData('base.teaser.headline')
            ?? $resource->getName();
        $teaser->text = $resource->getData('base.teaser.text');
        $teaser->contentType = $resource->getData('base.mime');
        $teaser->contentLength = $resource->getData('base.filesize');
        return $teaser;
    }
}
