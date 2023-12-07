<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input]
class IndexerInput
{
    #[GQL\Field(type:"String!")]
    public string $index;
    #[GQL\Field(type:"Int")]
    public ?int $cleanupThreshold;
    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $paths;
}
