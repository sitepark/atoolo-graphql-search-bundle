<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class DummyTeaserGetterResolver implements Resolver
{
    public function getFieldWithArgs(
        ArticleTeaser $teaser,
        ArgumentInterface $args
    ): ?string {
        return 'fieldvalue';
    }

    public function getFieldWithoutArgs(
        ArticleTeaser $teaser
    ): ?string {
        return 'fieldvalue';
    }

    public function nogetter(): void
    {
        // dummy
    }

    public function getWithNoArgs(): string
    {
        return 'noargs';
    }

    public function getWithThreeArgs(int $a, int $b, int $c): void
    {
        // dummy
    }

    public function getWithArgOneHasNoType($a): string // phpstan-ignore-line
    {
        return 'dummy';
    }

    public function getWithArgOneIsUnionType(int|string $a): string
    {
        return 'dummy';
    }

    public function getWithArgTwoAsNoType(
        int $a,
        $b // phpstan-ignore-line
    ): string {
        return 'dummy';
    }

    public function getWithArgTwoIsUnionType(int $a, int|string $b): string
    {
        return 'dummy';
    }

    public function getWihtArgTwoNamedTypeButNotArgumentInterface(
        int $a,
        int $b
    ): string {
        return 'dummy';
    }
}
