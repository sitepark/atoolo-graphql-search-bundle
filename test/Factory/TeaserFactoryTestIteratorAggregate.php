<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use ArrayIterator;
use Atoolo\GraphQL\Search\Resolver\TeaserFactory;
use IteratorAggregate;
use Traversable;

class TeaserFactoryTestIteratorAggregate implements IteratorAggregate
{
    /**
     * @param array<string,TeaserFactory> $factories
     */
    public function __construct(private readonly array $factories) {}
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->factories);
    }
}
