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

/**
 * @phpstan-type ImageData array{
 *     characteristic: ?string,
 *     copyright: ?string,
 *     text: ?string,
 *     legend: ?string,
 *     description: ?string,
 *     original : ?ImageSourceData,
 *     variants: ?array<string,array<ImageSourceData>>
 * }
 *
 * @phpstan-type ImageSourceData array{
 *     url: string,
 *     width: int,
 *     height: int,
 *     mediaQuery: ?string
 * }
 */
class ArticleTeaserResolver implements Resolver, TeaserResolver
{
    public function __construct(private readonly UrlRewriter $urlRewriter)
    {
    }

    public function accept(Resource $resource): bool
    {
        return true;
    }

    public function resolve(Resource $resource): Teaser
    {
        $teaser = new ArticleTeaser();
        $teaser->url = $this->urlRewriter->rewrite(
            UrlRewriterType::LINK,
            $resource->getLocation()
        );

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

        /** @var ImageData $imageData */
        $imageData = $teaser->resource->getData()->getAssociativeArray(
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

        /** @var string $variant */
        $variant = $args['variant'];

        if (
            $imageData['variants'] !== null &&
            is_array($imageData['variants'][$variant])
        ) {
            $image->sources = $this->toImageSourceList(
                $variant,
                $imageData['variants'][$variant]
            );
        } else {
            $image->sources = [];
        }
        return $image;
    }

    private function toImageCharacteristic(string $type): ImageCharacteristic
    {
        return ImageCharacteristic::valueOfCamelCase($type)
            ?? ImageCharacteristic::NORMAL;
    }

    /**
     * @param array<ImageSourceData> $variantData
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
     * @param ImageSourceData $sourceData
     */
    private function toImageSource(
        string $variant,
        array $sourceData
    ): ImageSource {
        $source = new ImageSource();
        $source->variant = $variant;
        $source->url = $this->urlRewriter->rewrite(
            UrlRewriterType::IMAGE,
            $sourceData['url']
        );
        $source->width = $sourceData['width'];
        $source->height = $sourceData['height'];
        $source->mediaQuery = $sourceData['mediaQuery'] ?? null;
        return $source;
    }
}
