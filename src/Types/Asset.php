<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

abstract class Asset
{
    public ?string $copyright;
    public ?string $caption;
    public ?string $description;
}
