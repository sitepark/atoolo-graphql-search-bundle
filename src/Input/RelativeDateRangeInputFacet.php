<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\DateRangeRound;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name:"RelativeDateRangeInputFacet")]
class RelativeDateRangeInputFacet
{
    #[GQL\Field(type:"DateTime")]
    public ?\DateTime $base = null;

    #[GQL\Field(type:"DateInterval")]
    public ?\DateInterval $before = null;

    #[GQL\Field(type:"DateInterval")]
    public ?\DateInterval $after = null;

    #[GQL\Field(type:"DateInterval")]
    public ?\DateInterval $gap = null;

    #[GQL\Field(type:"DateRangeRound")]
    public ?DateRangeRound $roundStart = null;

    #[GQL\Field(type:"DateRangeRound")]
    public ?DateRangeRound $roundEnd = null;
}
