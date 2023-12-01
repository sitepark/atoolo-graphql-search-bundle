<?php

namespace Atoolo\GraphQL\Search\Types;

use Overblog\GraphQLBundle\Annotation as GQL;

enum ImageCharacteristic
{
    case NORMAL;
    case INFOGRAPHIC;
    case DECORATIVE_IMAGE;
    case DECORATIVE_IMAGE_NOT_CUT;

    public static function valueOfCamelCase(
        string $name
    ): ?ImageCharacteristic {
        $name = ImageCharacteristic::camelCaseToSnakeCase(trim($name));
        foreach (ImageCharacteristic::cases() as $case) {
            if ($case->name == $name) {
                return $case;
            }
        }
        return null;
    }

    private static function camelCaseToSnakeCase(
        string $camelCaseString
    ): string {
        return strtoupper(preg_replace(
            '/(?<!^)[A-Z]/',
            '_$0',
            $camelCaseString
        ));
    }
}
