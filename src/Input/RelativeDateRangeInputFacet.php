<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name:"RelativeDateRangeInputFilter")]
class RelativeDateRangeInputFacet
{
    #[GQL\Field(type:"DateTime")]
    public ?\DateTime $base = null;

    #[GQL\Field(type:"DateInterval")]
    public ?\DateInterval $before = null;

    #[GQL\Field(type:"DateInterval")]
    public ?\DateInterval $after = null;
}
