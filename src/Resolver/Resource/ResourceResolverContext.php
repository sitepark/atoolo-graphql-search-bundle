<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

class ResourceResolverContext
{
    private ?string $resourceLocation = null;

    private bool $sameNavigation = false;

    public function setResourceLocation(string $resourceLocation): void
    {
        $this->resourceLocation = $resourceLocation;
    }

    public function getResourceLocation(): ?string
    {
        return $this->resourceLocation;
    }

    public function setSameNavigation(bool $sameNavigation): void
    {
        $this->sameNavigation = $sameNavigation;
    }

    public function isSameNavigation(): bool
    {
        return $this->sameNavigation;
    }
}
