<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Error;

use Overblog\GraphQLBundle\Error\ExceptionConverterInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Throwable;

final class ExceptionConverter implements ExceptionConverterInterface
{
    public function convertException(Throwable $exception): Throwable
    {
        return new UserError(
            'atoolo: ' . $exception->getMessage(),
            $exception->getCode(),
            $exception->getPrevious()
        );
    }
}
