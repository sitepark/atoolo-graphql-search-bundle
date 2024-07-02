<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ImageCharacteristic::class)]
class ImageCharacteristicTest extends TestCase
{
    public function testValueOfCamelCase(): void
    {
        $value = 'decorativeImage';
        $expected = ImageCharacteristic::DECORATIVE_IMAGE;
        self::assertSame(
            $expected,
            ImageCharacteristic::valueOfCamelCase($value),
        );
    }

    public function testValueOfCamelCaseWithInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ImageCharacteristic::valueOfCamelCase('invalidValue');
    }
}
