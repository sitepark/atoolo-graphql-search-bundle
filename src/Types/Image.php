<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Types;

class Image extends Asset
{
    /**
     * @param ImageSource[] $sources
     */
    public function __construct(
        ?string $copyright,
        ?string $caption,
        ?string $description,
        public ?string $alternativeText,
        public ?ImageSource $original,
        public ?ImageCharacteristic $characteristic,
        public array $sources
    ) {
        parent::__construct($copyright, $caption, $description);
    }
}
