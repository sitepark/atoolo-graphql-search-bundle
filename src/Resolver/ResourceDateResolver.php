<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\Resource\Resource;
use DateTime;

class ResourceDateResolver
{
    public function getDate(
        Resource $resource,
    ): ?DateTime {
        $timestamp = $resource->data->getInt('base.teaser.date');
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date;
    }
}
