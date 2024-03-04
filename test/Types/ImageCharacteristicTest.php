<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Types;

use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
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
            ImageCharacteristic::valueOfCamelCase($value)
        );
    }

    public function testValueOfCamelCaseWithInvalidValue(): void
    {
        $this->assertNull(
            ImageCharacteristic::valueOfCamelCase('invalidValue'),
            'Expected null for invalid value'
        );
    }
}
