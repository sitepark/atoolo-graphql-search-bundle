<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\MediaTeaser;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ResourceToTeaserResolver
{
    /**
     * @param iterable<TeaserResolver> $resolverList
     */
    public function __construct(
        private readonly iterable $resolverList
    ) {
    }

    public function resolve(Resource $resource): Teaser
    {
        foreach ($this->resolverList as $resolver) {
            if (!$resolver->accept($resource)) {
                continue;
            }
            return $resolver->resolve($resource);
        }

        throw new \InvalidArgumentException(
            'Unresolvable resource ' . $resource->getLocation()
        );
    }
}
