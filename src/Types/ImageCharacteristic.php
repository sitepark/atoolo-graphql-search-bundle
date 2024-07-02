<?php

namespace Atoolo\GraphQL\Search\Types;

use InvalidArgumentException;

enum ImageCharacteristic: string
{
    case NORMAL = 'NORMAL';
    case INFOGRAPHIC = 'INFOGRAPHIC';
    case DECORATIVE_IMAGE = 'DECORATIVE_IMAGE';
    case DECORATIVE_IMAGE_NOT_CUT = 'DECORATIVE_IMAGE_NOT_CUT';

    public static function valueOfCamelCase(
        string $name,
    ): ImageCharacteristic {
        $name = self::camelCaseToSnakeCase(trim($name));
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        throw new InvalidArgumentException(
            'unsupported name for ImageCharacteristic: ' . $name,
        );
    }

    private static function camelCaseToSnakeCase(
        string $camelCaseString,
    ): string {
        /** @var string $snakeCase */
        $snakeCase = preg_replace(
            '/(?<!^)[A-Z]/',
            '_$0',
            $camelCaseString,
        );
        return strtoupper($snakeCase);
    }
}
