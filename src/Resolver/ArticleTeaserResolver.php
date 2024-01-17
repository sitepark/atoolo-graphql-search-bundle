<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use Atoolo\GraphQL\Search\Types\Teaser;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class ArticleTeaserResolver implements Resolver, TeaserResolver
{

    public function accept(Resource $resource): bool
    {
        return true;
    }

    public function resolve(Resource $resource): Teaser
    {
        $teaser = new ArticleTeaser();
        $teaser->url = $resource->getLocation();
        $teaser->headline = $resource->getData()->getString(
            'base.teaser.headline',
            $resource->getName()
        );
        $teaser->text = $resource->getData()->getString('base.teaser.text');
        $teaser->resource = $resource;
        return $teaser;
    }

    public function getAsset(
        ArticleTeaser $teaser,
        ArgumentInterface $args
    ): ?Image {

        $imageData = $teaser->resource->getData(
            'base.teaser.image'
        );
        if (!is_array($imageData)) {
            return null;
        }

        $image = new Image();
        $image->characteristic = $this->toImageCharacteristic(
            $imageData['characteristic'] ?? 'normal'
        );

        $image->copyright = $imageData['copyright'] ?? null;
        $image->alternativeText = $imageData['text'] ?? null;
        $image->caption = $imageData['legend'] ?? null;
        $image->description = $imageData['description'] ?? null;

        if (isset($imageData['original'])) {
            $image->original = $this->toImageSource(
                'original',
                $imageData['original']
            );
        }

        if (
            isset($imageData['variants'][$args->variant]) &&
            is_array($imageData['variants'][$args->variant])
        ) {
            $image->sources = $this->toImageSourceList(
                $args->variant,
                $imageData['variants'][$args->variant]
            );
        } else {
            $image->sources = [];
        }
        return $image;
    }

    private function toImageCharacteristic(string $type): ImageCharacteristic
    {
        $characteristic = ImageCharacteristic::valueOfCamelCase($type);
        if ($characteristic !== null) {
            return $characteristic;
        }
        return ImageCharacteristic::NORMAL;
    }

    /**
     * @param array<array<string,string|int>> $sourceData
     * @return ImageSource[]
     */
    private function toImageSourceList(
        string $variant,
        array $variantData
    ): array {
        $sources = [];
        foreach (
            $variantData as $sourceData
        ) {
            $sources[] = $this->toImageSource(
                $variant,
                $sourceData
            );
        }
        return $sources;
    }

    /**
     * @param array<string,string|int> $sourceData
     */
    private function toImageSource(
        string $variant,
        array $sourceData
    ): ImageSource {
        $source = new ImageSource();
        $source->variant = $variant;
        $source->url = $sourceData['url'];
        $source->width = $sourceData['width'];
        $source->height = $sourceData['height'];
        $source->mediaQuery = $sourceData['mediaQuery'] ?? null;
        return $source;
    }

}
