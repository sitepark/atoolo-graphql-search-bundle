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

    #[GQL\Field(type:"Boolean")]
    public ?bool $archive = null;

    /**
     * @var array<InputFilter>
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $and = null;

    /**
     * @var array<InputFilter>
     */
    #[GQL\Field(type:"[InputFilter!]")]
    public ?array $or = null;

    #[GQL\Field(type:"InputFilter")]
    public ?InputFilter $not = null;

    #[GQL\Field(type:"String")]
    public ?string $query = null;
}
