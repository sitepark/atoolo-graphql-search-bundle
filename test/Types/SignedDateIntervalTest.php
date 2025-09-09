<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\SignedDateInterval;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SignedDateInterval::class)]
class SignedDateIntervalTest extends TestCase
{
    public function testSerialize(): void
    {
        $value = new \DateInterval('P1Y2M2DT2H30M10S');
        $value->invert = 1;
        self::assertSame(
            '-P1Y2M2DT2H30M10S',
            SignedDateInterval::serialize($value),
        );
    }

    public function testParseValue(): void
    {
        $expected = new \DateInterval('P1Y2M2DT2H30M10S');
        $expected->invert = 1;
        self::assertEquals(
            $expected,
            SignedDateInterval::parseValue('-P1Y2M2DT2H30M10S'),
        );
    }

    public function testParseInvalidValue(): void
    {
        $value = 'abc';
        $this->expectException(InvalidArgumentException::class);
        SignedDateInterval::parseValue($value);
    }

    public function testParseLiteral(): void
    {
        $valueNode = new StringValueNode(['value' => '-P1Y2M2DT2H30M10S']);
        $expected = new \DateInterval('P1Y2M2DT2H30M10S');
        $expected->invert = 1;
        self::assertEquals(
            $expected,
            SignedDateInterval::parseLiteral($valueNode),
        );
    }

    public function testParseLiteralWithInvalidNode(): void
    {
        $valueNode = $this->createStub(Node::class);
        $this->expectException(InvalidArgumentException::class);
        SignedDateInterval::parseLiteral($valueNode);
    }
}
