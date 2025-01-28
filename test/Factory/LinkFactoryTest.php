<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Factory;

use Atoolo\GraphQL\Search\Factory\LinkFactory;
use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Rewrite\Dto\UrlRewriteType;
use Atoolo\Rewrite\Service\UrlRewriter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LinkFactory::class)]
class LinkFactoryTest extends TestCase
{
    private LinkFactory $factory;

    private UrlRewriter&MockObject $urlRewriter;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->urlRewriter = $this->createMock(UrlRewriter::class);
        $this->factory = new LinkFactory(
            $this->urlRewriter,
        );
    }

    public function testCreate(): void
    {
        $url = '/some_url.php';
        $title = 'some_title';
        $resource = $this->createResource([
            'url' => $url,
            'base' => [
                'title' => $title,
            ],
        ]);
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->willReturn($url);
        $link = $this->factory->create($resource);

        $this->assertEquals($url, $link->url);
        $this->assertEquals($title, $link->label);
        $this->assertNull($link->accessibilityLabel);
        $this->assertNull($link->description);
        $this->assertFalse($link->opensNewWindow);
        $this->assertFalse($link->isExternal);
    }

    public function testCreateWithoutTitle(): void
    {
        $url = '/some_url.php';
        $name = 'some_name';
        $resource = $this->createResource([
            'url' => $url,
            'name' => $name,
        ]);
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->willReturn($url);
        $link = $this->factory->create($resource);
        $this->assertEquals($name, $link->label);
    }

    public function testCreateWithMediaResource(): void
    {
        $mediaUrl = '/some_url.jpg';
        $resource = $this->createResource([
            'objectType' => 'media',
            'mediaUrl' => $mediaUrl,
        ]);
        $resource2 = $this->createResource([
            'objectType' => 'embedded-media',
            'mediaUrl' => $mediaUrl,
        ]);
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->with(UrlRewriteType::MEDIA, $mediaUrl)
            ->willReturn($mediaUrl);
        $link = $this->factory->create($resource);
        $this->assertEquals($mediaUrl, $link->url);

        $link2 = $this->factory->create($resource2);
        $this->assertEquals($mediaUrl, $link2->url);
    }

    public function testCreateWithExternal(): void
    {
        $url = '/some_url.php';
        $label = 'external_label';
        $accessibilityLabel = 'accessibility_label';
        $description = 'description';
        $resource = $this->createResource([
            'url' => $url,
            'base' => [
                'title' => 'some_title',
            ],
            'external' => [
                'label' => $label,
                'accessibilityLabel' => $accessibilityLabel,
                'description' => $description,
                'newWindow' => true,
                'external' => true,
            ],
        ]);
        $this->urlRewriter
            ->expects($this->atLeastOnce())
            ->method('rewrite')
            ->willReturn($url);
        $link = $this->factory->create($resource);

        $this->assertEquals($url, $link->url);
        $this->assertEquals($label, $link->label);
        $this->assertEquals($accessibilityLabel, $link->accessibilityLabel);
        $this->assertEquals($description, $link->description);
        $this->assertTrue($link->opensNewWindow);
        $this->assertTrue($link->isExternal);
    }



    private function createResource(array $data): Resource
    {
        return new Resource(
            $data['url'] ?? '',
            $data['id'] ?? '',
            $data['name'] ?? '',
            $data['objectType'] ?? '',
            ResourceLanguage::default(),
            new DataBag($data),
        );
    }
}
