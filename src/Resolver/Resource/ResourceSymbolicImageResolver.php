<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Resolver\Resource;

use Atoolo\GraphQL\Search\Factory\AssetFactory;
use Atoolo\GraphQL\Search\Resolver\Resolver;
use Atoolo\GraphQL\Search\Types\SymbolicImage;
use Atoolo\Resource\Resource;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Psr\Log\LoggerInterface;

class ResourceSymbolicImageResolver implements Resolver
{
    public function __construct(
        private readonly AssetFactory $symbolicImageFactory,
        private readonly LoggerInterface $logger,
    ) {}

    public function getSymbolicImage(
        Resource $resource,
        ArgumentInterface $args,
    ): ?SymbolicImage {
        if (isset($args['variant']) && !is_string($args['variant'])) {
            throw new \InvalidArgumentException(
                'argument \'variant\' must be of type string',
            );
        }
        /** @var ?string $variant */
        $variant = $args['variant'] ?? null;
        $asset = $this->symbolicImageFactory->create(
            $resource,
            $variant,
        );
        if ($asset !== null && !($asset instanceof SymbolicImage)) {
            $this->logger->error(
                'the asset factory provided did not create a SymbolicImage',
                [
                    'expected' => SymbolicImage::class,
                    'actual' => $asset::class,
                    'symbolicImageFactory' => $this->symbolicImageFactory,
                ],
            );
            return null;
        }
        return $asset;
    }
}
