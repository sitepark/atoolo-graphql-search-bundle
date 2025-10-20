<?php

namespace Atoolo\GraphQL\Search\Console\Command;

use Atoolo\GraphQL\Search\DependencyInjection\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @codeCoverageIgnore
 */
#[AsCommand(name: Configuration::NAME . ':list-query-dirs')]
class ListQueryDirs extends Command
{
    public function __construct(
        private readonly ParameterBagInterface $params,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Lists all directories where the bundle'
            . ' will search for graphql queries (.graphql files)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string[] $queryDirs */
        $queryDirs = $this->params->get(Configuration::NAME . '.graphql_query_dirs');

        if (empty($queryDirs)) {
            $output->writeln('<comment>No GraphQL query directories are configured.</comment>');
            return Command::SUCCESS;
        }

        $output->writeln('<info>Configured GraphQL query directories:</info>');
        foreach ($queryDirs as $dir) {
            $output->writeln(' - ' . $dir);
        }

        return Command::SUCCESS;
    }
}
