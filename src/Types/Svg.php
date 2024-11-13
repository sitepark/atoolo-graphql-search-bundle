<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class Svg extends Asset
{
    public function __construct(
        public readonly string $url,
    ) {
        parent::__construct(null, null, null, null);
    }
}
