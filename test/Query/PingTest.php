<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Query;

use Atoolo\GraphQL\Search\Query\Ping;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Ping::class)]
class PingTest extends TestCase
{
    public function testPing(): void
    {
        $ping = new Ping();
        $this->assertEquals(
            'pong',
            $ping->ping(),
            'pong expected',
        );
    }
}
