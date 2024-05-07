<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name:"InputFacet")]
class InputFacet
{
    #[GQL\Field(type:"String!")]
    public string $key;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $excludeFilter = null;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $objectTypes = null;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $contentSectionTypes = null;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $categories = null;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $sites = null;

    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public ?array $groups = null;

    #[GQL\Field(type:"AbsoluteDateRangeInputFacet")]
    public ?AbsoluteDateRangeInputFacet $absoluteDateRange = null;

    #[GQL\Field(type:"RelativeDateRangeInputFacet")]
    public ?RelativeDateRangeInputFacet $relativeDateRange = null;
}
