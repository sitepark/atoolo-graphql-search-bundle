<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver;

use Atoolo\GraphQL\Search\Types\ArticleTeaser;
use Atoolo\GraphQL\Search\Types\Asset;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use InvalidArgumentException;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-type ImageData array{
 *     characteristic?: string,
 *     copyright?: string,
 *     text?: string,
 *     legend?: string,
 *     description?: string,
 *     original? : ImageSourceData,
 *     variants?: array<string,array<ImageSourceData>>
 * }
 *
 * @phpstan-type ImageSourceData array{
 *     url: string,
 *     width: int,
 *     height: int,
 *     mediaQuery?: string
 * }
 */
class ArticleTeaserResolver implements Resolver
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getAsset(
        ArticleTeaser $teaser,
        ArgumentInterface $args
    ): ?Asset {

        /** @var ImageData|array{} $imageData */
        $imageData = $teaser->resource->getData()->getAssociativeArray(
            'base.teaser.image'
        );
        if (empty($imageData)) {
            return null;
        }

        try {
            $characteristic = ImageCharacteristic::valueOfCamelCase(
                $imageData['characteristic'] ?? 'normal'
            );
        } catch (InvalidArgumentException $e) {
            $this->logger->error(
                'Invalid characteristic for teaser image ' .
                'of resource ' . $teaser->resource->getLocation(),
                ['exception' => $e]
            );
            return null;
        }

        $copyright = $imageData['copyright'] ?? null;
        $alternativeText = $imageData['text'] ?? null;
        $caption = $imageData['legend'] ?? null;
        $description = $imageData['description'] ?? null;
        $original = null;
        if (isset($imageData['original'])) {
            $original = $this->toImageSource(
                'original',
                $imageData['original']
            );
        }

        /** @var string $variant */
        $variant = $args['variant'];

        $sources = [];
        if (
            isset($imageData['variants']) &&
            is_array($imageData['variants'][$variant])
        ) {
            $sources = $this->toImageSourceList(
                $variant,
                $imageData['variants'][$variant]
            );
        }

        return new Image(
            $copyright,
            $caption,
            $description,
            $alternativeText,
            $original,
            $characteristic,
            $sources
        );
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
        foreach ($variantData as $sourceData) {
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
        $url = $this->urlRewriter->rewrite(
            UrlRewriterType::IMAGE,
            $sourceData['url']
        );
        $width = $sourceData['width'];
        $height = $sourceData['height'];
        $mediaQuery = $sourceData['mediaQuery'] ?? null;

        return new ImageSource(
            $variant,
            $url,
            $width,
            $height,
            $mediaQuery
        );
    }
}
