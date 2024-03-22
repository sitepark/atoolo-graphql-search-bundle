<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

class DoNothingUrlRewriter implements UrlRewriter
{
    public function rewrite(UrlRewriterType $type, string $url): string
    {
        return $url;
    }
}
