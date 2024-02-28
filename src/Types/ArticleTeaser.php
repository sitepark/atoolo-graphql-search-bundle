<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Atoolo\Resource\Resource;

class ArticleTeaser extends Teaser
{
    public function __construct(
        ?string $url,
        public readonly ?string $headline,
        public readonly ?string $text,
        public readonly ?Asset $asset,
        public readonly Resource $resource
    ) {
        parent::__construct($url);
    }
}
