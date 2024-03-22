<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Query;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class Ping
{
    #[GQL\Query(name: 'ping', type: 'String!')]
    public function ping(): string
    {
        return "pong";
    }
}
