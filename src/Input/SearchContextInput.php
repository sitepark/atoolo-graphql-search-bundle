<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "SearchContextInput")]
class SearchContextInput
{
    #[GQL\Field(type: "String")]
    public ?string $urlBasePath = null;

    #[GQL\Field(type: "SearchContextOptionsInput")]
    public ?SearchContextOptionsInput $options = null;

}
