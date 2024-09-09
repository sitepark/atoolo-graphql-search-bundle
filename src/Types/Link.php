<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

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
        public readonly bool $opensNewWindow = false,
        public readonly bool $isExternal = false,
    ) {}
}
