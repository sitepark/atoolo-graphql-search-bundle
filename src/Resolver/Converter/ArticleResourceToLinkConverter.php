<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Converter;

use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;

class ArticleResourceToLinkConverter implements ResourceToLinkConverter
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
    ) {}

    public function convert(Resource $resource): ?Link
    {
        return new Link(
            $this->getUrl($resource),
            $this->getLabel($resource),
            $this->getAriaLabel($resource),
            $this->getDescription($resource),
            $this->opensNewWindow($resource),
            $this->isExternal($resource),
        );
    }

    protected function getUrl(Resource $resource): string
    {
        return $this->urlRewriter->rewrite(
            UrlRewriterType::LINK,
            $resource->location,
        );
    }

    protected function getLabel(Resource $resource): ?string
    {
        return $resource->data->getString(
            'external.label',
            $resource->data->getString(
                'base.title',
                $resource->data->getString('name'),
            ),
        );
    }

    protected function getAriaLabel(Resource $resource): ?string
    {
        return $resource->data->has('external.accessibilityLabel')
            ? $resource->data->getString('external.accessibilityLabel')
            : null;
    }

    protected function getDescription(Resource $resource): ?string
    {
        return $resource->data->has('external.description')
            ? $resource->data->getString('external.description')
            : null;
    }

    protected function opensNewWindow(Resource $resource): bool
    {
        return $resource->data->getBool('external.newWindow', false);
    }

    protected function isExternal(Resource $resource): bool
    {
        return $resource->data->getBool('external.external', false);
    }
}
