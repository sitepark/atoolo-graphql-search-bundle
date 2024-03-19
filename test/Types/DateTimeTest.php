<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\DateTime;
use DateTimeInterface;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateTime::class)]
class DateTimeTest extends TestCase
{
    public function testSerialize(): void
    {
        $value = new \DateTime('2021-01-01T00:00:00+00:00');
        self::assertSame(
            '2021-01-01T00:00:00+00:00',
            DateTime::serialize($value)
        );
    }

    public function testParseValue(): void
    {
        $value = '2021-01-01T00:00:00+00:00';
        $dateTime = DateTime::parseValue($value);
        self::assertSame(
            $value,
            $dateTime->format(DateTimeInterface::RFC3339)
        );
    }

    public function testParseLiteral(): void
    {
        $value = '2021-01-01T00:00:00+00:00';
        $valueNode = new StringValueNode(['value' => $value]);
        $dateTime = DateTime::parseLiteral($valueNode);
        self::assertSame(
            $value,
            $dateTime->format(DateTimeInterface::RFC3339)
        );
    }

    public function testParseLiteralWithInvalidNode(): void
    {
        $valueNode = $this->createStub(Node::class);
        $this->expectException(InvalidArgumentException::class);
        DateTime::parseLiteral($valueNode);
    }
}
