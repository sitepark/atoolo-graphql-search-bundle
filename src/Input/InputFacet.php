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

    #[GQL\Field(type:"String")]
    public ?string $excludeFilter = null;

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
}
