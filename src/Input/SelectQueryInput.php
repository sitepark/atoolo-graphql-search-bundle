<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\QueryDefaultOperator;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input]
class SelectQueryInput
{
    #[GQL\Field(type:"String!")]
    public ?string $index;
    #[GQL\Field(type:"String")]
    public string $text;
    #[GQL\Field(type:"Int")]
    public int $offset;
    #[GQL\Field(type:"Int")]
    public int $limit;
    #[GQL\Field(type:"QueryDefaultOperator")]
    public QueryDefaultOperator $queryDefaultOperator;
    /**
     * @var InputFilter[]
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public array $filter;
    /**
     * @var InputFacet[]
     */
    #[GQL\Field(type:"[InputFacet!]")]
    public array $facets;
}
