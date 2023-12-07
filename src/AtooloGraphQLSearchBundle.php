<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search;

use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
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

        $configDir = __DIR__ . '/Resources/config';

        $loader = new GlobFileLoader(new FileLocator($configDir));
        $loader->setResolver(
            new LoaderResolver(
                [
                    new YamlFileLoader($container, new FileLocator($configDir)),
                ]
            )
        );

        $loader->load('*.yaml', 'glob');
    }
}
