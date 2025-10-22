<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\DependencyInjection\Compiler;

use Atoolo\GraphQL\Search\DependencyInjection\Configuration;
use Atoolo\GraphQL\Search\Service\GraphQLOperationManager;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\Parser;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * @codeCoverageIgnore
 */
class GraphQLQueryLoaderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(GraphQLOperationManager::class)) {
            return;
        }
        $managerDefinition = $container->findDefinition(GraphQLOperationManager::class);

        $queryDirs = $this->getQueryDirectoriesToScan($container);
        if (empty($queryDirs)) {
            $managerDefinition->setArgument('$operationsRaw', []);
            return;
        }

        $finder = new Finder();
        $finder->files()->in($queryDirs)->name('*.graphql');
        $operations = [];
        foreach ($finder as $file) {
            $fileContents = $file->getContents();
            try {
                $abstractSyntaxTree = Parser::parse($fileContents);
            } catch (\Exception $e) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Failed to parse GraphQL file "%s": %s',
                        $file->getRealPath(),
                        $e->getMessage(),
                    ),
                    0,
                    $e,
                );
            }

            foreach ($abstractSyntaxTree->definitions as $astDefinition) {
                // We only care about executable operations (query, mutation)
                if ($astDefinition instanceof OperationDefinitionNode) {
                    $operationName = $astDefinition->name?->value;
                    if ($operationName !== null && isset($operations[$operationName])) {
                        throw new InvalidArgumentException(
                            sprintf(
                                'Duplicate GraphQL operation name "%s" found in file "%s".'
                                    . ' Operation names must be unique.',
                                $operationName,
                                $file->getRealPath(),
                            ),
                        );
                    }
                    $operations[$operationName] = [
                        'name' => $operationName,
                        'source' => $fileContents,
                        'sourceLocation' => $file->getPathname(),
                    ];
                }
            }
        }
        $managerDefinition->setArgument('$operationsRaw', $operations);
    }

    /**
     * @return string[]
     */
    private function getQueryDirectoriesToScan(ContainerBuilder $container): array
    {
        /** @var string[] $queryDirs */
        $queryDirs = $container->getParameter(Configuration::NAME . '.graphql_query_dirs');
        foreach ($queryDirs as $queryDir) {
            if (!is_dir($queryDir)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Invalid directory "%s". Either create it or remove'
                            . ' the entry from %s.graphql_query_dirs',
                        $queryDir,
                        Configuration::NAME,
                    ),
                );
            }
        }
        return $queryDirs;
    }
}
