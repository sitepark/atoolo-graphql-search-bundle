<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
abstract class TeaserFeature
{
    public function __construct(
        public readonly ?string $label = null,
    ) {}
}
