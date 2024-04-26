<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\DateInterval;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateInterval::class)]
class DateIntervalTest extends TestCase
{
    public function testSerialize(): void
    {
        $value = new \DateInterval('P1Y2M2DT2H30M10S');
        self::assertSame(
            'P1Y2M2DT2H30M10S',
            DateInterval::serialize($value)
        );
    }

    public function testParseValue(): void
    {
        $value = 'P1Y2M2DT2H30M10S';
        self::assertEquals(
            new \DateInterval($value),
            DateInterval::parseValue('P1Y2M2DT2H30M10S')
        );
    }

    public function testParseInvalidValue(): void
    {
        $value = 'abc';
        $this->expectException(InvalidArgumentException::class);
        DateInterval::parseValue($value);
    }

    public function testParseLiteral(): void
    {
        $value = 'P1Y2M2DT2H30M10S';
        $valueNode = new StringValueNode(['value' => $value]);
        self::assertEquals(
            new \DateInterval($value),
            DateInterval::parseLiteral($valueNode)
        );
    }

    public function testParseLiteralWithInvalidNode(): void
    {
        $valueNode = $this->createStub(Node::class);
        $this->expectException(InvalidArgumentException::class);
        DateInterval::parseLiteral($valueNode);
    }
}
