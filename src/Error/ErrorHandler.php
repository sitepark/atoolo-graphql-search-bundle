<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Error;

use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use Overblog\GraphQLBundle\Event\ExecutorResultEvent;

class ErrorHandler
{
    public function onPostExecutor(ExecutorResultEvent $event): void
    {
        $myErrorFormatter = function (Error $error) {
            return FormattedError::createFromException($error);
        };

        $myErrorHandler = function (array $errors, callable $formatter) {
            return array_map($formatter, $errors);
        };

        $event->getResult()
            ->setErrorFormatter($myErrorFormatter)
            ->setErrorsHandler($myErrorHandler);
    }
}
