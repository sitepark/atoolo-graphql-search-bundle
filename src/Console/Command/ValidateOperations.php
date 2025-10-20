<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Console\Command;

use Atoolo\GraphQL\Search\DependencyInjection\Configuration;
use Atoolo\GraphQL\Search\Service\GraphQLOperationManager;
use Atoolo\GraphQL\Search\Service\GraphQLOperationValidator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 */
#[AsCommand(name: Configuration::NAME . ':validate-operations')]
class ValidateOperations extends Command
{
    public function __construct(
        private readonly GraphQLOperationManager $graphQLOperationManager,
        private readonly GraphQLOperationValidator $graphQLOperationValidator,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Validates all availlable graphql operations loaded from'
            . ' .graphql files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $operations = $this->graphQLOperationManager->getOperations();
        $errorsPerOperation = [];
        foreach ($operations as $operationName => $operation) {
            $errors = $this->graphQLOperationValidator->validate($operation);
            if (!empty($errors)) {
                $errorsPerOperation[$operationName] = $errors;
            }
        }
        if (!empty($errorsPerOperation)) {
            $output->writeln(PHP_EOL . '<error>Validation failed for the following operations:</error>' . PHP_EOL);
            foreach ($errorsPerOperation as $operationName => $errorMessages) {
                $output->writeln(
                    sprintf(
                        "<info>Operation '%s', (%s):</info>",
                        $operationName,
                        $operations[$operationName]->sourceLocation,
                    ),
                );
                foreach ($errorMessages as $errorMessage) {
                    $output->writeln(sprintf("  - %s", $errorMessage));
                }
                $output->writeln('');
            }
            return Command::FAILURE;
        }

        $output->writeln('');
        $output->writeln('<info>All graphql operations are valid</info>');
        $output->writeln('');
        return Command::SUCCESS;
    }
}
