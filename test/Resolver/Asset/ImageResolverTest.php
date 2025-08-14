<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Asset;

use Atoolo\GraphQL\Search\Resolver\Asset\ImageResolver;
use Atoolo\GraphQL\Search\Types\Image;
use Atoolo\GraphQL\Search\Types\ImageSource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(ImageResolver::class)]
class ImageResolverTest extends TestCase
{
    private ImageResolver $resolver;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->resolver = new ImageResolver();
    }

    public function testGetAsset(): void
    {
        $staticImageSource = new ImageSource('variant', '/static_image', 100, 100);
        $image = new Image(
            'copyright',
            null,
            'caption',
            'description',
            'alternativeText',
            new ImageSource('variant', '/original_image', 100, 100),
            null,
            [],
            $staticImageSource,
        );
        $result = $this->resolver->getStaticImage($image);
        $this->assertEquals(
            $staticImageSource,
            $result,
            'resolver should return the static image source of the image',
        );
    }
}
