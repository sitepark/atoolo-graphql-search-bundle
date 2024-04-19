<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\MediaTeaser;

class MediaTeaserResolver implements Resolver
{
    public function __construct(
        private readonly ArticleTeaserResolver $articleTeaserResolver
    ) {
    }

    public function getKicker(
        MediaTeaser $teaser
    ): ?string {
        return $this->articleTeaserResolver->getKickerFromResource(
            $teaser->resource
        );
    }
}
