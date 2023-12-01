<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class AtooloGraphQLSearchBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->setParameter(
            'atoolo_graphql_search_bundle_src_dir',
            __DIR__
        );

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/Resources/config')
        );
        $loader->load('services.yaml');
        $loader->load('graphql.yaml');
        $loader->load('graphiql.yaml');
    }
}
