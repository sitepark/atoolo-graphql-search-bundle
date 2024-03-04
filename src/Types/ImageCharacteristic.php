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
        string $name
    ): ?ImageCharacteristic {
        $name = self::camelCaseToSnakeCase(trim($name));
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        return null;
    }

    private static function camelCaseToSnakeCase(
        string $camelCaseString
    ): string {
        $snakeCase = preg_replace(
            '/(?<!^)[A-Z]/',
            '_$0',
            $camelCaseString
        );
        // @codeCoverageIgnoreStart
        if (!is_string($snakeCase)) {
            throw new InvalidArgumentException(
                'unable to transform string from camel-case to snake-case'
            );
        }
        // @codeCoverageIgnoreEnd
        return strtoupper($snakeCase);
    }
}
