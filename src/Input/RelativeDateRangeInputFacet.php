<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Atoolo\GraphQL\Search\Types\DateRangeRound;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "RelativeDateRangeInputFacet")]
class RelativeDateRangeInputFacet
{
    #[GQL\Field(type: "DateTime")]
    #[GQL\Description("Defaults to the current datetime if null")]
    public ?\DateTime $base = null;

    #[GQL\Field(type: "SignedDateInterval")]
    public ?\DateInterval $baseOffset = null;

    #[GQL\Field(type: "DateInterval")]
    public ?\DateInterval $before = null;

    #[GQL\Field(type: "DateInterval")]
    public ?\DateInterval $after = null;

    #[GQL\Field(type: "DateInterval")]
    public ?\DateInterval $gap = null;

    #[GQL\Field(type: "DateRangeRound")]
    public ?DateRangeRound $roundStart = null;

    #[GQL\Field(type: "DateRangeRound")]
    public ?DateRangeRound $roundEnd = null;
}
