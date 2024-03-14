<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input]
class IndexerInput
{
    #[GQL\Field(type:"String!")]
    public string $index;

    #[GQL\Field(type:"Int")]
    public int $cleanupThreshold = 0;

    #[GQL\Field(type:"Int")]
    public int $chunkSize = 500;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $paths = null;
}
