<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

/**
 * @codeCoverageIgnore
 */
enum UrlRewriterType
{
    case IMAGE;
    case MEDIA;
    case LINK;
}
