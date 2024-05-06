<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name:"AbsoluteDateRangeInputFilter")]
class AbsoluteDateRangeInputFacet
{
    #[GQL\Field(type:"DateTime")]
    public ?\DateTime $from = null;

    #[GQL\Field(type:"DateTime")]
    public ?\DateTime $to = null;
}
