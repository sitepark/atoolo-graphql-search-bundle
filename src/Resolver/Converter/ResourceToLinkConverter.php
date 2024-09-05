<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Converter;

use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;

interface ResourceToLinkConverter
{
    public function convert(Resource $resource): ?Link;
}
