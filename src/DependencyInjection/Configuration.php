<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    public const string NAME = 'atoolo_graphql';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('atoolo_graphql');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('graphql_query_dirs')
                    ->info('A list of directories to scan for .graphql query files.'
                        . ' Those will then be loaded and made availlable to the GraphQLOperationManager.')
                    ->scalarPrototype()->cannotBeEmpty()->end()
                    ->defaultValue([])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
