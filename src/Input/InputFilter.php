<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name:"InputFilter")]
class InputFilter
{
    #[GQL\Field(type:"String")]
    public string $key;
    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public array $objectTypes;
    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public array $contentSectionTypes;
    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public array $categories;
    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public array $site;
    /**
     * @var string[]
     */
    #[GQL\Field(type:"[String!]")]
    public array $group;
    #[GQL\Field(type:"Boolean")]
    public bool $archive;
}
