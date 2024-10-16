<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\DelegatingTeaserFactory;
use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\Hierarchy;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;

/**
 * @phpstan-type ExplainDetail array{
 *    score: float,
 *    type: string,
 *    field: string|null,
 *    description: string,
 *    details?: array<array<string,mixed>>,
 *  }
 */
class ResourceResolver implements Resolver
{
    public function __construct(
        private readonly DelegatingTeaserFactory $teaserFactory,
    ) {}

    public function getTeaser(Resource $resource): Teaser
    {
        return $this->teaserFactory->create($resource);
    }

    public function getNavigation(Resource $resource): Hierarchy
    {
        return new Hierarchy('navigation', $resource);
    }

    /**
     * @param Resource $resource
     * @return ExplainDetail
     */
    public function getExplain(Resource $resource): ?array
    {
        if (!$resource->data->has('explain')) {
            return null;
        }
        /** @var ExplainDetail $explain */
        $explain = $resource->data->getArray('explain');
        return $explain;
    }

    /**
     * @return string[]
     */
    public function getContentSectionTypes(Resource $resource): array
    {
        /** @var string[] $contentSectionTypes */
        $contentSectionTypes = $resource->data->getArray(
            'contentSectionTypes',
        );
        return $contentSectionTypes;
    }
}
