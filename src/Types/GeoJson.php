<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\ObjectValueNode;
use InvalidArgumentException;
use JsonException;
use Overblog\GraphQLBundle\Annotation as GQL;
use RuntimeException;

/**
 * @phpstan-type JsonArray array<string, mixed>|array<string|bool|int|float|null|array<string, mixed>>
 * This is specified by overblog/GraphQLBundle
 * https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/type-system/scalars.md#custom-scalar
 */
#[GQL\Scalar('GeoJson')]
class GeoJson
{
    /**
     * @param JsonArray $value
     * @return JsonArray
     */
    public static function serialize(array $value): array
    {
        return $value;
    }

    /**
     * @param JsonArray $value
     * @return JsonArray
     */
    public static function parseValue(array $value): array
    {
        return $value;
    }

    /**
     * @return JsonArray
     * @throws JsonException
     */
    public static function parseLiteral(Node $valueNode): array
    {
        if (!$valueNode instanceof ObjectValueNode) {
            throw new InvalidArgumentException('GeoJson must be a object');
        }

        throw new RuntimeException('Not implemented');
    }
}
