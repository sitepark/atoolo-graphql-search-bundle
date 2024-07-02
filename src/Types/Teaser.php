<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
abstract class Teaser
{
    public function __construct(
        public readonly ?string $url,
    ) {}
}
