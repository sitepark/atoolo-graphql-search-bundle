<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Error;

use Overblog\GraphQLBundle\Event\ErrorFormattingEvent;

class ErrorCode
{
    public function onErrorFormatting(ErrorFormattingEvent $event): void
    {
        $error = $event->getError();
        if ($error->getPrevious()) {
            $code = $error->getPrevious()->getCode();
        } else {
            $code = $error->getCode();
        }
        $formattedError = $event->getFormattedError();
        $formattedError->offsetSet('code', $code); // or $formattedError['code'] = $code;
    }
}
