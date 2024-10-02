<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\GraphQL\Search\Resolver\UrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use Atoolo\GraphQL\Search\Types\CopyrightDetails;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-type CopyrightLinkData array{
 *      url: string,
 *      label?: string,
 *  }
 * @phpstan-type CopyrightDetailsData array{
 *     original?: CopyrightLinkData,
 *     author?: CopyrightLinkData,
 *     license?: CopyrightLinkData,
 * }
 * @phpstan-type ImageData array{
 *     characteristic?: string,
 *     copyright?: string,
 *     copyrightDetails?: CopyrightDetailsData,
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

class ImageFactory implements AssetFactory
{
    public function __construct(
        private readonly UrlRewriter $urlRewriter,
        private readonly LoggerInterface $logger,
    ) {}

    public function create(
        Resource $resource,
        ?string $variant = null,
    ): ?Image {
        if ($variant === null) {
            $this->logger->error(
                'variant must not be null when creating an Image',
                [
                    'resource' => $resource->location,
                ],
            );
            return null;
        }

        /** @var ImageData $imageData */
        $imageData = $resource->data->getAssociativeArray(
            'base.teaser.image',
        );
        if (empty($imageData)) {
            return null;
        }

        try {
            $characteristic = ImageCharacteristic::valueOfCamelCase(
                $imageData['characteristic'] ?? 'normal',
            );
        } catch (InvalidArgumentException $e) {
            $this->logger->error(
                'Invalid characteristic for teaser image',
                [
                    'resource' => $resource->location,
                    'exception' => $e,
                ],
            );
            return null;
        }

        $copyright = $imageData['copyright'] ?? null;
        $copyrightDetails = $this->toCopyrightDetails(
            $imageData['copyrightDetails'] ?? null,
        );
        $alternativeText = $imageData['text'] ?? null;
        $caption = $imageData['legend'] ?? null;
        $description = $imageData['description'] ?? null;
        $original = null;
        if (isset($imageData['original'])) {
            $original = $this->toImageSource(
                'original',
                $imageData['original'],
            );
        }

        $sources = [];
        if (
            isset($imageData['variants']) &&
            is_array($imageData['variants'][$variant])
        ) {
            $sources = $this->toImageSourceList(
                $variant,
                $imageData['variants'][$variant],
            );
        }

        return new Image(
            $copyright,
            $copyrightDetails,
            $caption,
            $description,
            $alternativeText,
            $original,
            $characteristic,
            $sources,
        );
    }

    /**
     * @param array<ImageSourceData> $variantData
     * @return ImageSource[]
     */
    private function toImageSourceList(
        string $variant,
        array $variantData,
    ): array {
        $sources = [];
        foreach ($variantData as $sourceData) {
            $sources[] = $this->toImageSource(
                $variant,
                $sourceData,
            );
        }
        return $sources;
    }

    /**
     * @param ImageSourceData $sourceData
     */
    private function toImageSource(
        string $variant,
        array $sourceData,
    ): ImageSource {
        $url = $this->urlRewriter->rewrite(
            UrlRewriterType::IMAGE,
            $sourceData['url'],
        );
        $width = $sourceData['width'];
        $height = $sourceData['height'];
        $mediaQuery = $sourceData['mediaQuery'] ?? null;

        return new ImageSource(
            $variant,
            $url,
            $width,
            $height,
            $mediaQuery,
        );
    }

    /**
     * @param CopyrightDetailsData|null $data
     */
    private function toCopyrightDetails(?array $data): ?CopyrightDetails
    {
        return empty($data) ? null : new CopyrightDetails(
            original: $this->toLink($data['original'] ?? null),
            author: $this->toLink($data['author'] ?? null),
            license: $this->toLink($data['license'] ?? null),
        );
    }

    /**
     * @param CopyrightLinkData|null $data
     */
    private function toLink(?array $data): ?Link
    {
        return empty($data['url']) ? null : new Link(
            url: $data['url'],
            label: $data['label'] ?? null,
            accessibilityLabel: null,
            description: null,
            opensNewWindow: true,
            isExternal: true,
        );
    }
}
