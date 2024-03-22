<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

interface UrlRewriter
{
    public function rewrite(UrlRewriterType $type, string $url): string;
}
