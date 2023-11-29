<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use ArrayObject;
use ReflectionClass;
use ReflectionMethod;

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
 * resolvers that are tagged via `atoolo.graphql.resolver`.
 */
class ResolverMapRegistry extends ResolverMap
{
    /**
     * @var array<string,array<string,ResolverMethod>
     */
    private ?array $resolverMap = null;
    /**
     * @param iterable<Resolver> $resolverList
     */
    public function __construct(
        private readonly iterable $resolverList
    ) {
    }

    protected function map(): array
    {
        if ($this->resolverMap === null) {
            $this->resolverMap = $this->loadResolverMap();
        }
        $map = [];
        foreach ($this->resolverMap as $typeName => $fieldMap) {
            $map[$typeName] = [
                self::RESOLVE_FIELD => $this->buildResolverFunction($fieldMap)
            ];
        }
        return $map;
    }

    /**
     * @return array<string,array<string,ResolverMethod>
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
     * @param array<string,ResolverMethod> $fieldMap
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

    private function resolveProperty(
        object $obj,
        string $fieldName
    ): mixed {
        $reflectionClass = new ReflectionClass(get_class($obj));
        return $reflectionClass
            ->getProperty($fieldName)
            ->getValue($obj);
    }

    public function getResolverGetterMap(Resolver $resolver): array
    {
        $class = new ReflectionClass(get_class($resolver));
        $publicMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $map = [];
        foreach ($publicMethods as $method) {
            if (!$this->isResolverGetter($method)) {
                continue;
            }
            $param = $method->getParameters()[0];
            if ($param->getType() === null) {
                continue;
            }
            $fullTypeName = $param->getType()->getName();
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
        return $map;
    }

    private function isResolverGetter(ReflectionMethod $method): bool
    {
        if (!str_starts_with($method->getName(), 'get')) {
            return false;
        }

        $params = $method->getParameters();
        $paramLength = count($params);
        if ($paramLength === 0) {
            return false;
        }

        if ($params[0]->getType() === null) {
            return false;
        }

        if ($paramLength === 1) {
            return true;
        }

        if ($paramLength > 2) {
            return false;
        }

        if ($params[1]->getType() === null) {
            return false;
        }

        if ($params[1]->getType()->getName() !== ArgumentInterface::class) {
            return false;
        }

        return true;
    }
}
