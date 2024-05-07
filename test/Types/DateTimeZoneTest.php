<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\DateTimeZone;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateTimeZone::class)]
class DateTimeZoneTest extends TestCase
{
    public function testSerialize(): void
    {
        $value = new \DateTimeZone('Europe/Berlin');
        self::assertSame(
            'Europe/Berlin',
            DateTimeZone::serialize($value)
        );
    }

    public function testParseValue(): void
    {
        $value = 'Europe/Berlin';
        $timeZone = DateTimeZone::parseValue($value);
        self::assertSame(
            $value,
            $timeZone->getName()
        );
    }

    public function testParseInvalidValue(): void
    {
        $value = 'abc';
        $this->expectException(InvalidArgumentException::class);
        DateTimeZone::parseValue($value);
    }

    public function testParseLiteral(): void
    {
        $value = 'Europe/Berlin';
        $valueNode = new StringValueNode(['value' => $value]);
        $timeZone = DateTimeZone::parseLiteral($valueNode);
        self::assertSame(
            $value,
            $timeZone->getName()
        );
    }

    public function testParseLiteralWithInvalidNode(): void
    {
        $valueNode = $this->createStub(Node::class);
        $this->expectException(InvalidArgumentException::class);
        DateTimeZone::parseLiteral($valueNode);
    }
}
