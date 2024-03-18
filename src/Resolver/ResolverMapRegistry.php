<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use ArrayObject;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;

// phpcs:disable
/**
 * Defining resolvers to extend individual fields is a bit cumbersome and not
 * easy to extend. The best method here is to use the ResolverMap. See
 * - {@link https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/resolver.md resolver.md}
 * - {@link https://github.com/overblog/GraphQLBundle/blob/master/docs/definitions/resolver-map.md resolver-map.md}
 *
 * Inspired by the possibilities in Java (see
 * {@link https://www.graphql-java-kickstart.com/tools/relay/#creating-the-resolver creating-the-resolver}),
 * this class takes on the task of recording all resolvers, reading out their
 * getter methods as field resolvers via reflection and returning them as a
 * ResolverMap.
 *
 * The ResolverMapRegistry is registered as a service and collects all
 * resolvers that are tagged via `atoolo_graphql_search.resolver`.
 *
 * @phpstan-type ResolverFieldFunction Closure(
 *     mixed,
 *     ArgumentInterface,
 *     ArrayObject<int, string>,
 *     ResolveInfo
 * ): mixed
 * @phpstan-type ResolverTypeFunction Closure(mixed): string
 * @phpstan-type ResolverMapEntry array{
 *     '%%resolveField': ResolverFieldFunction
 * }|array{
 *     '%%resolveType': ResolverTypeFunction
 * }
 */
// phpcs:enable
class ResolverMapRegistry extends ResolverMap
{
    /**
     * @param iterable<Resolver> $resolverList
     */
    public function __construct(
        private readonly iterable $resolverList
    ) {
    }

    /**
     * @return non-empty-array<string, ResolverMapEntry>
     */
    protected function map(): array
    {
        $resolverMap = $this->loadResolverMap();
        $map = [];
        foreach ($resolverMap as $typeName => $fieldMap) {
            $map[$typeName] = [
                self::RESOLVE_FIELD => $this->buildResolverFunction($fieldMap),
            ];
        }

        $map['Teaser'] = [
            self::RESOLVE_TYPE => function ($value) {
                return $this->resolveType($value);
            }
        ];
        $map['Asset'] = [
            self::RESOLVE_TYPE => function ($value) {
                return $this->resolveType($value);
            }
        ];
        return $map;
    }

    private function resolveType(object $value): string
    {
        $className = get_class($value);
        return (substr($className, strrpos($className, '\\') + 1));
    }

    /**
     * @return array<string, non-empty-array<string, ResolverMethod>>
     */
    private function loadResolverMap(): array
    {
        $mapList = [];
        foreach ($this->resolverList as $resolver) {
            $mapList[] = $this->getResolverGetterMap($resolver);
        }
        return array_merge_recursive(
            ...$mapList
        );
    }

    /**
     * @param array<string, ResolverMethod> $fieldMap
     * @return ResolverFieldFunction
     */
    private function buildResolverFunction(array $fieldMap): Closure
    {
        return function (
            $value,
            ArgumentInterface $args,
            ArrayObject $context,
            ResolveInfo $info
        ) use ($fieldMap) {
            $fieldName = $info->fieldName;
            if (isset($fieldMap[$fieldName])) {
                $resolverMethod = $fieldMap[$fieldName];
                return $this->invokeGetter(
                    $resolverMethod,
                    $value,
                    $args
                );
            }

            return $this->resolveProperty($value, $fieldName);
        };
    }

    /**
     * @throws ReflectionException
     */
    private function invokeGetter(
        ResolverMethod $resolverMethod,
        object $obj,
        ArgumentInterface $args
    ): mixed {
        $method = $resolverMethod->reflectionMethod;
        $params = $method->getParameters();
        $paramLength = count($params);
        if ($paramLength === 1) {
            return $method->invoke($resolverMethod->resolver, $obj);
        }

        return $method->invoke($resolverMethod->resolver, $obj, $args);
    }

    /**
     * @throws ReflectionException
     */
    private function resolveProperty(
        object $obj,
        string $fieldName
    ): mixed {
        $reflectionClass = new ReflectionClass(get_class($obj));
        return $reflectionClass
            ->getProperty($fieldName)
            ->getValue($obj);
    }

    /**
     * @return array<string, non-empty-array<string, ResolverMethod>>
     */
    public function getResolverGetterMap(Resolver $resolver): array
    {
        $class = new ReflectionClass(get_class($resolver));
        $publicMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $map = [];
        foreach ($publicMethods as $method) {
            $types = $this->getTypesToResolve($method);
            if (empty($types)) {
                continue;
            }

            foreach ($types as $fullTypeName) {
                $typeName = substr(
                    $fullTypeName,
                    strrpos($fullTypeName, '\\') + 1
                );
                $fieldName = lcfirst(substr($method->getName(), 3));

                $map[$typeName][$fieldName] = new ResolverMethod(
                    $resolver,
                    $fieldName,
                    $method
                );
            }
        }
        return $map;
    }

    /**
     * @return array<string>
     */
    private function getTypesToResolve(ReflectionMethod $method): array
    {
        if (!str_starts_with($method->getName(), 'get')) {
            return [];
        }

        $params = $method->getParameters();
        $paramLength = count($params);
        if ($paramLength === 0 || $paramLength > 2) {
            return [];
        }

        if ($paramLength === 2) {
            $argumentsType = $params[1]->getType();
            if (
                !($argumentsType instanceof ReflectionNamedType) ||
                $argumentsType->getName() !== ArgumentInterface::class
            ) {
                return [];
            }
        }
        $objectType = $params[0]->getType();
        if ($objectType instanceof ReflectionNamedType) {
            return [$objectType->getName()];
        }
        if ($objectType instanceof ReflectionUnionType) {
            return array_map(
                static fn($type) => $type->getName(),
                $objectType->getTypes()
            );
        }

        return [];
    }
}
