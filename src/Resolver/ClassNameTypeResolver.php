<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

// phpcs:disable
/**
 * For the GraphQL interfaces, the specific class must be determined using a
 * data set.
 * Type resolvers are used for this purpose. See
 *
 * {@link https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/type-system/interface.md#using-resolvetype using-resolvetype}
 *
 * This `ClassNameTypeResolver` determines the class name of the object and
 * returns the class name without namespaces.
 *
 * This TypeResolver is registered as a service with the alias `typeByClassName`
 * and can be specified as a PHP attribute for GraphQL interfaces. GraphQL
 * interfaces are defined as abstract classes in PHP.
 *
 * ```
 * #[GQL\TypeInterface(
 *    name:'Asset',
 *    resolveType:"@=query('typeByClassName', value)"
 * )]
 * abstract class Asset
 * {
 * // ...
 * }
 * ```
 */
// phpcs:enable
class ClassNameTypeResolver
{
    public function resolveType(object $value): string
    {
        $className = get_class($value);
        return (substr($className, strrpos($className, '\\') + 1));
    }
}
