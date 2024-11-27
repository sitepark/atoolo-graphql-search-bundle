<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\ActionLinkFactory;
use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;

class ResourceActionLinkResolver implements Resolver
{
    /**
     * @param array<ActionLinkFactory> $factories
     */
    public function __construct(
        private readonly iterable $factories,
    ) {}

    /**
     * @return Link[]
     */
    public function getActionLinks(
        Resource $resource,
    ): array {
        $links = [];
        foreach ($this->factories as $factory) {
            foreach ($factory->create($resource) as $createdLink) {
                if ($createdLink !== null) {
                    $links[] = $createdLink;
                }
            }
        }
        return $links;
    }
}
