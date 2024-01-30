<?php

namespace Atoolo\GraphQL\Search\Types;

use InvalidArgumentException;

enum ImageCharacteristic
{
    case NORMAL;
    case INFOGRAPHIC;
    case DECORATIVE_IMAGE;
    case DECORATIVE_IMAGE_NOT_CUT;

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
        if (!is_string($snakeCase)) {
            throw new InvalidArgumentException(
                'unable to transform string from camel-case to snake-case'
            );
        }
        return strtoupper($snakeCase);
    }
}
