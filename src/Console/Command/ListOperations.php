<?php

namespace Atoolo\GraphQL\Search\Console\Command;

use Atoolo\GraphQL\Search\DependencyInjection\Configuration;
use Atoolo\GraphQL\Search\Service\GraphQLOperationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @codeCoverageIgnore
 */
#[AsCommand(name: Configuration::NAME . ':list-operations')]
class ListOperations extends Command
{
    public function __construct(
        private readonly GraphQLOperationManager $queryManager,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Lists all availlable graphql query operations loaded from the available .graphql files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $operations = $this->queryManager->getOperations();

        $output->writeln('');
        if (empty($operations)) {
            $output->writeln('<comment>No GraphQL query operations are configured.</comment>');
            return Command::SUCCESS;
        }

        $output->writeln('<info>Availlable GraphQL operations:</info>');
        foreach ($operations as $operation) {
            $output->writeln(
                ' - ' . $operation->name . ' (location: ' . $operation->sourceLocation . ')',
            );
        }
        $output->writeln('');

        return Command::SUCCESS;
    }
}
