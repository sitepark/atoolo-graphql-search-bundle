<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\ActionLinkFactory;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceActionLinkResolver;
use Atoolo\GraphQL\Search\Resolver\Resource\ResourceSymbolicAssetResolver;
use Atoolo\GraphQL\Search\Test\TestResourceFactory;
use Atoolo\GraphQL\Search\Types\Link;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

#[CoversClass(ResourceActionLinkResolver::class)]
class ResourceActionLinkResolverTest extends TestCase
{
    private ResourceActionLinkResolver $resolver;

    private ActionLinkFactory&MockObject $firstActionLinkFactory;

    private ActionLinkFactory&MockObject $lastActionLinkFactory;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->firstActionLinkFactory = $this->createMock(
            ActionLinkFactory::class,
        );
        $this->lastActionLinkFactory =  $this->createMock(
            ActionLinkFactory::class,
        );
        $this->resolver = new ResourceActionLinkResolver(
            [
                $this->firstActionLinkFactory,
                $this->lastActionLinkFactory,
            ],
        );
    }

    public function testGetActionLinks(): void
    {
        $resource = $this->createResource([]);
        $args = $this->createStub(ArgumentInterface::class);
        $this->firstActionLinkFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource)
            ->willReturn(
                [
                    new Link('first_url'),
                    null,
                    new Link('first_other_url'),
                ],
            );
        $this->lastActionLinkFactory
            ->expects($this->once())
            ->method('create')
            ->with($resource)
            ->willReturn(
                [
                    null,
                    new Link('last_url'),
                    new Link('last_url'),
                ],
            );

        $result = $this->resolver->getActionLinks($resource, $args);
        $this->assertEquals(
            [
                new Link('first_url'),
                new Link('first_other_url'),
                new Link('last_url'),
                new Link('last_url'),
            ],
            $result,
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
