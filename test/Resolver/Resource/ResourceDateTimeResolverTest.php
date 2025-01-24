<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Resolver\Resource\ResourceDateTimeResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\Resource\Resource;
use DateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceDateTimeResolver::class)]
class ResourceDateTimeResolverTest extends TestCase
{
    private ResourceDateTimeResolver $resolver;

    public function setUp(): void
    {
        $this->resolver = new ResourceDateTimeResolver();
    }

    public function testGetDate(): void
    {
        $date = new DateTime();
        $date->setDate(19, 4, 2024);
        $date->setTime(9, 28);

        $resource = $this->createResource([
            'base' => [
                'teaser' => [
                    'date' => $date->getTimestamp(),
                ],
            ],
        ]);

        $teaserDate = $this->resolver->getDate($resource);

        $this->assertEquals(
            $date,
            $teaserDate,
            'unexpected teaser date',
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private function createResource(array $data): Resource
    {
        return TestResourceFactory::create($data);
    }
}
