<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

class ArticleTeaser extends Teaser
{
    public ?string $headline;

    public ?string $text;

    public ?Asset $asset;

    public \Atoolo\Resource\Resource $resource;
}
