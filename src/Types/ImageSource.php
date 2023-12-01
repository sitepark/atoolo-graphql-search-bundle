<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

class ImageSource
{
    #[GQL\Field]
    public string $variant;

    #[GQL\Field]
    #[GQL\Description("url of the image")]
    public string $url;

    #[GQL\Field]
    public int $width;

    #[GQL\Field]
    public int $height;

    #[GQL\Field]
    public ?string $mediaQuery = null;
}
