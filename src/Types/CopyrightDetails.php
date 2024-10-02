<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class CopyrightDetails
{
    public function __construct(
        public readonly ?Link $original,
        public readonly ?Link $author,
        public readonly ?Link $license,
    ) {}
}
