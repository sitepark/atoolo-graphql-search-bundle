<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @codeCoverageIgnore
 */
#[GQL\Input(name: "TeaserPropertyInputFilter")]
class TeaserPropertyInputFilter
{
    #[GQL\Field(type: "Boolean")]
    public ?bool $image = null;

    #[GQL\Field(type: "Boolean")]
    public ?bool $imageCopyright = null;

    #[GQL\Field(type: "Boolean")]
    public ?bool $headline = null;

    #[GQL\Field(type: "Boolean")]
    public ?bool $text = null;
}
