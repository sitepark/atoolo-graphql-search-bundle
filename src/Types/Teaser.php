<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\TypeInterface(
    name:'Teaser',
    resolveType:"@=query('typeByClassName', value)"
)]
#[GQL\Description("Base Type for all Teaser")]
abstract class Teaser
{
    #[GQL\Field]
    #[GQL\Description("Url of the teaser")]
    public ?string $url;

}
