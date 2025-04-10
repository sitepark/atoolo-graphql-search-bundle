<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "InputFacet")]
class InputFacet
{
    #[GQL\Field(type: "String!")]
    public string $key;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $excludeFilter = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $objectTypes = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $contentSectionTypes = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $categories = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $sites = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $sources = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $contentTypes = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type: "[String!]")]
    public ?array $groups = null;

    #[GQL\Field(type: "AbsoluteDateRangeInputFacet")]
    public ?AbsoluteDateRangeInputFacet $absoluteDateRange = null;

    #[GQL\Field(type: "RelativeDateRangeInputFacet")]
    public ?RelativeDateRangeInputFacet $relativeDateRange = null;

    #[GQL\Field(type: "SpatialDistanceRangeInputFacet")]
    public ?SpatialDistanceRangeInputFacet $spatialDistanceRange = null;

    #[GQL\Field(type: "String")]
    public ?string $query = null;

    #[GQL\Field(type: "QueryTemplateInput")]
    public ?QueryTemplateInput $queryTemplate = null;

}
