<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(name:'Image', interfaces: ['Asset'])]
class Image extends Asset
{
    #[GQL\Field]
    public ?string $alternativeText;
    #[GQL\Field]
    public ?ImageSource $original;
    #[GQL\Field]
    public ?ImageCharacteristic $characteristic;
    /**
     * @var ImageSource[]
     */
    #[GQL\Field]
    public array $sources;
}
