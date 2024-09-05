<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\Resource\Resource;
use DateTime;

class ResourceDateTimeResolver implements Resolver
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
