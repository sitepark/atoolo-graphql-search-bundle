<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Service;

use Atoolo\GraphQL\Search\Dto\GraphQLOperation;
use GraphQL\Language\Parser;
use GraphQL\Validator\DocumentValidator;
use Overblog\GraphQLBundle\Request\Executor;

class GraphQLOperationValidator
{
    public function __construct(
        /** overblog graphql executor injected to access the available schemas */
        private readonly Executor $executor,
    ) {}

    /**
     * @return array<string> error messages, empty if no error occured
     */
    public function validate(GraphQLOperation $operation): array
    {
        $schema = $this->executor->getSchema();
        $validationErrors = [];
        try {
            $ast = Parser::parse($operation->source);
            $errors = DocumentValidator::validate($schema, $ast);

            if (count($errors) > 0) {
                $errorMessages = array_map(
                    fn(\GraphQL\Error\Error $e) => $e->getMessage(),
                    $errors,
                );
                $validationErrors = $errorMessages;
            }
        } catch (\Exception $e) {
            $validationErrors = [$e->getMessage()];
        }
        return $validationErrors;
    }
}
