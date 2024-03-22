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
    #[GQL\Field(type:"Int")]
    public ?int $cleanupThreshold = null;

    #[GQL\Field(type:"Int")]
    public ?int $chunkSize = null;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $paths = null;
}
