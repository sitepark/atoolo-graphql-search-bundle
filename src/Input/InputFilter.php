<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name:"InputFilter")]
class InputFilter
{
    #[GQL\Field(type:"String")]
    public ?string $key = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $ids = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $objectTypes = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $contentSectionTypes = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $categories = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $sites = null;

    /**
     * @var ?array<string>
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $groups = null;

    #[GQL\Field(type:"AbsoluteDateRangeInputFilter")]
    public ?AbsoluteDateRangeInputFilter $absoluteDateRange = null;

    #[GQL\Field(type:"RelativeDateRangeInputFilter")]
    public ?RelativeDateRangeInputFilter $relativeDateRange = null;

    #[GQL\Field(type:"Boolean!")]
    public ?bool $geoLocatedFilter = null;

    #[GQL\Field(type:"SpatialOrbitalInputFilter")]
    public ?SpatialOrbitalInputFilter $spatialOrbital = null;

    #[GQL\Field(type:"SpatialArbitraryRectangleInputFilter")]
    public ?SpatialArbitraryRectangleInputFilter $spatialArbitraryRectangle = null;

    /**
     * @var ?array<InputFilter>
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $and = null;

    /**
     * @var ?array<InputFilter>
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $or = null;

    #[GQL\Field(type:"InputFilter")]
    public ?InputFilter $not = null;

    #[GQL\Field(type:"String")]
    public ?string $query = null;
}
