<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class TeaserFeature
{
    public function __construct(
        public readonly ?string $label = null,
        public readonly ?Link $link = null,
    ) {}
}
