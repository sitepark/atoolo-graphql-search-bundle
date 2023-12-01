<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

//#[GQL\Type(name:'Resource')]
class Resource
{
    public ?string $id;
    public ?string $name;
    public ?string $location;
}
