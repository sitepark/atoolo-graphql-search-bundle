<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Atoolo\Resource\Resource;

/**
 * @codeCoverageIgnore
 */
class MediaTeaser extends Teaser
{
    public function __construct(
        ?string $url,
        public readonly ?string $headline,
        public readonly ?string $text,
        public ?string $contentType,
        public ?int $contentLength,
        public readonly ?Asset $asset,
        public readonly Resource $resource
    ) {
        parent::__construct($url);
    }
}
