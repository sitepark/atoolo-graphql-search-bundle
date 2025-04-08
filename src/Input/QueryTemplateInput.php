<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "QueryTemplateInput")]
class QueryTemplateInput
{
    #[GQL\Field(type: "String!")]
    public ?string $query = null;

    /**
     * @var array<string,mixed>
     */
    #[GQL\Field(type: "Json!")]
    public ?array $variables = null;
}
