<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(name:'MediaTeaser', interfaces: ['Teaser'])]
class MediaTeaser extends Teaser
{
    #[GQL\Field]
    #[GQL\Description("headline of the teaser")]
    public ?string $headline;

    #[GQL\Field]
    #[GQL\Description("Text of the teaser")]
    public ?string $text;

    #[GQL\Field]
    #[GQL\Description("Media Content-Type")]
    public string $contentType;

    #[GQL\Field]
    #[GQL\Description("Media Content-Length")]
    public int $contentLength;
}
