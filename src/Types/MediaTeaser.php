<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

class MediaTeaser extends Teaser
{
    public ?string $headline;

    public ?string $text;

    public string $contentType;

    public int $contentLength;
}
