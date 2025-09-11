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
    #[GQL\Description("Sets the lower date boundary. Implicitely directed toward the past.")]
    #[GQL\Deprecated("Use 'from' instead to controll the direction of the interval")]
    public ?\DateInterval $before = null;

    #[GQL\Field(type: "DateInterval")]
    #[GQL\Description("Sets the upper date boundary. Implicitely directed toward the future.")]
    #[GQL\Deprecated("Use 'to' instead  to controll the direction of the interval")]
    public ?\DateInterval $after = null;

    #[GQL\Field(type: "SignedDateInterval")]
    #[GQL\Description("Sets the lower date boundary")]
    public ?\DateInterval $from = null;

    #[GQL\Field(type: "SignedDateInterval")]
    #[GQL\Description("Sets the upper date boundary")]
    public ?\DateInterval $to = null;

    #[GQL\Field(type: "DateInterval")]
    public ?\DateInterval $gap = null;

    #[GQL\Field(type: "DateRangeRound")]
    public ?DateRangeRound $roundStart = null;

    #[GQL\Field(type: "DateRangeRound")]
    public ?DateRangeRound $roundEnd = null;
}
