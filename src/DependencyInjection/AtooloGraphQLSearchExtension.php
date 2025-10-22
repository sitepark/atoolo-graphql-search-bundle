<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @codeCoverageIgnore
 */
class AtooloGraphQLSearchExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /** @var array{ graphql_query_dirs: array<string>} $config */
        $container->setParameter(
            $this->getAlias() . '.graphql_query_dirs',
            $config['graphql_query_dirs'],
        );
    }

    public function getAlias(): string
    {
        return Configuration::NAME;
    }
}
