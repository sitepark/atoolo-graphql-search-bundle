<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(name:'ArticleTeaser', interfaces: ['Teaser'])]
class ArticleTeaser extends Teaser
{
    #[GQL\Field]
    #[GQL\Description("headline of the teaser")]
    public ?string $headline;

    #[GQL\Field]
    #[GQL\Description("Text of the teaser")]
    public ?string $text;

    #[GQL\Field]
    #[GQL\Arg(name:'variant', type:'String!')]
    #[GQL\Description("Asset of the teaser. E.g. a image or a video")]
    public ?Asset $asset;

    public \Atoolo\Resource\Resource $resource;
}
