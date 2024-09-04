<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Atoolo\Resource\Resource;

/**
 * @codeCoverageIgnore
 */
class Link
{
    public function __construct(
        public readonly string $url,
        public readonly ?string $label = null,
        public readonly ?string $ariaLabel = null,
        public readonly ?string $description = null,
        public readonly ?bool $opensNewWindow = null,
        public readonly ?bool $isExternal = null,
        public readonly ?Resource $resource = null,
    ) {}
}
