<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Factory;

use Atoolo\GraphQL\Search\Types\CopyrightDetails;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageCharacteristic;
use Atoolo\GraphQL\Search\Types\ImageSource;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Rewrite\Dto\UrlRewriteOptions;
use Atoolo\Rewrite\Dto\UrlRewriteType;
use Atoolo\Rewrite\Service\UrlRewriter;
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
 *     mediaQuery?: string,
 *     static?: bool
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
            $resource->lang,
            $imageData['copyrightDetails'] ?? null,
        );
        $alternativeText = $imageData['text'] ?? null;
        $caption = $imageData['legend'] ?? null;
        $description = $imageData['description'] ?? null;
        $original = null;
        if (isset($imageData['original'])) {
            $original = $this->toImageSource(
                $resource->lang,
                'original',
                $imageData['original'],
            );
        }

        $sources = [];
        $static = null;
        foreach (($imageData['variants'][$variant] ?? []) as $sourceData) {
            $source = $this->toImageSource(
                $resource->lang,
                $variant,
                $sourceData,
            );
            $sources[] = $source;
            if ($sourceData['static'] ?? false) {
                $static = $source;
            }
        }
        // if no static is set explicitely, use first source or original
        $static ??= $sources[0] ?? $original;

        return new Image(
            $copyright,
            $copyrightDetails,
            $caption,
            $description,
            $alternativeText,
            $original,
            $characteristic,
            $sources,
            $static,
        );
    }

    /**
     * @param ImageSourceData $sourceData
     */
    private function toImageSource(
        ResourceLanguage $lang,
        string $variant,
        array $sourceData,
    ): ImageSource {
        $url = $this->urlRewriter->rewrite(
            UrlRewriteType::IMAGE,
            $sourceData['url'],
            UrlRewriteOptions::builder()->lang($lang->code)->build(),
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
    private function toCopyrightDetails(ResourceLanguage $lang, ?array $data): ?CopyrightDetails
    {
        return empty($data) ? null : new CopyrightDetails(
            original: $this->toLink($lang, $data['original'] ?? null),
            author: $this->toLink($lang, $data['author'] ?? null),
            license: $this->toLink($lang, $data['license'] ?? null),
        );
    }

    /**
     * @param CopyrightLinkData|null $data
     */
    private function toLink(ResourceLanguage $lang, ?array $data): ?Link
    {
        if (empty($data['url'])) {
            return null;
        }

        $url = $this->urlRewriter->rewrite(
            UrlRewriteType::IMAGE,
            $data['url'],
            UrlRewriteOptions::builder()->lang($lang->code)->build(),
        );

        return new Link(
            url: $url,
            label: $data['label'] ?? null,
            accessibilityLabel: null,
            description: null,
            opensNewWindow: true,
            isExternal: true,
        );
    }
}
