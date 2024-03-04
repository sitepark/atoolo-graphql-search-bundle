<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\DateTimeType;
use DateTime;
use DateTimeInterface;
use GraphQL\Language\AST\Node;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateTimeType::class)]
class DateTimeTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $value = new DateTime('2021-01-01T00:00:00+00:00');
        self::assertSame(
            '2021-01-01T00:00:00+00:00',
            DateTimeType::serialize($value)
        );
    }

    public function testParseValue(): void
    {
        $value = '2021-01-01T00:00:00+00:00';
        $dateTime = DateTimeType::parseValue($value);
        self::assertSame(
            $value,
            $dateTime->format(DateTimeInterface::RFC3339)
        );
    }

    public function testParseLiteral(): void
    {
        $value = '2021-01-01T00:00:00+00:00';
        $valueNode = $this->createStub(Node::class);
        $valueNode->value = $value; // phsptan-ignore-line
        $dateTime = DateTimeType::parseLiteral($valueNode);
        self::assertSame(
            $value,
            $dateTime->format(DateTimeInterface::RFC3339)
        );
    }
}
