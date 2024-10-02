<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

/**
 * @codeCoverageIgnore
 */
class Image extends Asset
{
    /**
     * @param ImageSource[] $sources
     */
    public function __construct(
        ?string $copyright,
        ?CopyrightDetails $copyrightDetails,
        ?string $caption,
        ?string $description,
        public readonly ?string $alternativeText,
        public readonly ?ImageSource $original,
        public readonly ?ImageCharacteristic $characteristic,
        public readonly array $sources,
    ) {
        parent::__construct($copyright, $copyrightDetails, $caption, $description);
    }
}
