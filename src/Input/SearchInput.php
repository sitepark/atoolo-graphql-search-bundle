<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\QueryOperator;
use DateTimeZone;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input]
class SearchInput
{
    #[GQL\Field(type: "String")]
    public ?string $text = null;

    #[GQL\Field(type: "Int")]
    public ?int $offset = null;

    #[GQL\Field(type: "Int")]
    public ?int $limit = null;

    #[GQL\Field(type: "String")]
    public ?string $lang = null;

    #[GQL\Field(type: "QueryOperator")]
    public ?QueryOperator $defaultQueryOperator = null;

    /**
     * @var ?array<InputSortCriteria>
     */
    #[GQL\Field(type: "[InputSortCriteria!]")]
    public ?array $sort = null;

    /**
     * @var ?array<InputFilter>
     */
    #[GQL\Field(type: "[InputFilter!]")]
    public ?array $filter = null;

    /**
     * @var ?array<InputFacet>
     */
    #[GQL\Field(type: "[InputFacet!]")]
    public ?array $facets = null;

    #[GQL\Field(type: "Boolean")]
    public ?bool $spellcheck = null;
    #[GQL\Field(type: "Boolean")]
    public ?bool $archive = null;

    #[GQL\Field(type: "DateTimeZone")]
    public ?DateTimeZone $timeZone = null;

    #[GQL\Field(type: "InputBoosting")]
    public ?InputBoosting $boosting = null;

    #[GQL\Field(type: "InputGeoPoint")]
    public ?InputGeoPoint $distanceReferencePoint = null;

    #[GQL\Field(type: "Boolean")]
    public bool $explain = false;
}
