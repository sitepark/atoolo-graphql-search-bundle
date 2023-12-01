<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

class Image extends Asset
{
    public ?string $alternativeText;
    public ?ImageSource $original;
    public ?ImageCharacteristic $characteristic;
    /**
     * @var ImageSource[]
     */
    public array $sources;
}
