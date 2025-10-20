<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search;

use Atoolo\GraphQL\Search\DependencyInjection\AtooloGraphQLSearchExtension;
use Atoolo\GraphQL\Search\DependencyInjection\Compiler\GraphQLQueryLoaderCompilerPass;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore
 */
class AtooloGraphQLSearchBundle extends Bundle
{
    /**
     * @throws Exception
     */
    public function build(ContainerBuilder $container): void
    {
        $configDir = realpath(__DIR__ . '/../config');
        $srcDir = realpath(__DIR__ . '/../src');

        if ($configDir === false) {
            throw new \LogicException(sprintf('config dir "%s" does not exist', $configDir));
        }
        if ($srcDir === false) {
            throw new \LogicException(sprintf('src dir "%s" does not exist', $configDir));
        }

        $container->setParameter(
            'atoolo_graphql_search.src_dir',
            $srcDir,
        );
        $container->setParameter(
            'atoolo_graphql_search.config_dir',
            $configDir,
        );
        $fileLocator = new FileLocator($configDir);
        $loader = new GlobFileLoader($fileLocator);
        $loader->setResolver(
            new LoaderResolver(
                [
                    new YamlFileLoader($container, $fileLocator),
                ],
            ),
        );
        $loader->load('services.yaml');
        $loader->load('graphql.yaml');

        $container->addCompilerPass(new GraphQLQueryLoaderCompilerPass());
    }

    public function getContainerExtension(): ?AtooloGraphQLSearchExtension
    {
        return new AtooloGraphQLSearchExtension();
    }
}
