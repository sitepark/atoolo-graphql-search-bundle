<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class ImageSource
{
    public function __construct(
        public readonly string $variant,
        public readonly string $url,
        public readonly int $width,
        public readonly int $height,
        public readonly ?string $mediaQuery = null,
    ) {}
}
