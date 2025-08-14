<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageSource;

class ImageResolver implements Resolver
{
    public function getStaticImage(
        Image $image,
    ): ?ImageSource {
        return $image->static;
    }
}
