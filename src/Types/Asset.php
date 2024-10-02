<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
abstract class Asset
{
    public function __construct(
        public readonly ?string $copyright,
        public readonly ?CopyrightDetails $copyrightDetails,
        public readonly ?string $caption,
        public readonly ?string $description,
    ) {}
}
