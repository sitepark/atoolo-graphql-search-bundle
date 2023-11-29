<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

//#[GQL\Type(name:'Resource')]
class Resource
{
    #[GQL\Field]
    public ?string $id;
    #[GQL\Field]
    public ?string $name;
    #[GQL\Field]
    public ?string $location;
}
