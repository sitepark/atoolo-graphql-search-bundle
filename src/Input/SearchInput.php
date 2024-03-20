<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\QueryOperator;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input]
class SearchInput
{
    #[GQL\Field(type:"String")]
    public string $index;

    #[GQL\Field(type:"String")]
    public ?string $text = null;

    #[GQL\Field(type:"Int")]
    public ?int $offset = null;

    #[GQL\Field(type:"Int")]
    public ?int $limit = null;

    #[GQL\Field(type:"QueryOperator")]
    public ?QueryOperator $defaultQueryOperator = null;

    /**
     * @var InputSortCriteria[]
     */
    #[GQL\Field(type:"[InputSortCriteria!]")]
    public ?array $sort = null;

    /**
     * @var InputFilter[]
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $filter = null;

    /**
     * @var InputFacet[]
     */
    #[GQL\Field(type:"[InputFacet!]")]
    public ?array $facets = null;
}
