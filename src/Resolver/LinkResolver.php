<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\Link;

class LinkResolver implements Resolver
{
    public function getLabel(
        Link $link,
    ): ?string {
        return $link->label
            ?? $link->resource?->data->getString(
                'external.label',
                $link->resource?->data->getString('name') ?? '',
            );
    }

    public function getAriaLabel(
        Link $link,
    ): ?string {
        if ($link->ariaLabel !== null) {
            return $link->ariaLabel;
        }
        if ($link->resource?->data->has('external.accessibilityLabel')) {
            return  $link->resource->data->getString('external.accessibilityLabel');
        }
        return null;
    }

    public function getDescription(
        Link $link,
    ): ?string {
        if ($link->description !== null) {
            return $link->description;
        }
        if ($link->resource?->data->has('external.description')) {
            return  $link->resource->data->getString('external.description');
        }
        return null;
    }

    public function getOpensNewWindow(
        Link $link,
    ): bool {
        return $link->opensNewWindow
            ?? $link->resource?->data->getBool('external.newWindow', false)
            ?? false;
    }

    public function getIsExternal(
        Link $link,
    ): bool {
        return $link->isExternal
            ?? $link->resource?->data->getBool('external.external', false)
            ?? true;
    }
}
