<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "SearchContextOptionsInput")]
class SearchContextOptionsInput
{
    #[GQL\Field(type: "Boolean")]
    public ?bool $sameNavigation = null;
}
