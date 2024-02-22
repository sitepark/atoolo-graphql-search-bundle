<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input]
class SuggestInput
{
    #[GQL\Field(type:"String")]
    public string $index;
    #[GQL\Field(type:"String")]
    public string $text;
    #[GQL\Field(type:"Int")]
    public ?int $limit;
    /**
     * @var InputFilter[]
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $filter;
}
