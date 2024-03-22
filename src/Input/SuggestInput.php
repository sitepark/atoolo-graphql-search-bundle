<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input]
class SuggestInput
{
    #[GQL\Field(type:"String!")]
    public string $text;

    #[GQL\Field(type:"Int")]
    public ?int $limit = null;

    #[GQL\Field(type:"String")]
    public ?string $lang = null;

    /**
     * @var InputFilter[]
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $filter = null;
}
