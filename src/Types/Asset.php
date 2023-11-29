<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;
#[GQL\TypeInterface(
    name:'Asset',
    resolveType:"@=query('typeByClassName', value)"
)]
abstract class Asset
{
    #[GQL\Field]
    public ?string $copyright;
    #[GQL\Field]
    public ?string $caption;
    #[GQL\Field]
    public ?string $description;
}
