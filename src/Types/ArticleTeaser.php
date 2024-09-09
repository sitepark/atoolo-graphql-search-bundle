<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Atoolo\Resource\Resource;

/**
 * @codeCoverageIgnore
 */
class ArticleTeaser extends Teaser
{
    public function __construct(
        ?Link $link,
        public readonly ?string $headline,
        public readonly ?string $text,
        public readonly Resource $resource,
    ) {
        parent::__construct($link);
    }
}
