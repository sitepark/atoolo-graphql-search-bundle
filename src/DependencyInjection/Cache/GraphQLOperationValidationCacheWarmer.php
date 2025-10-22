<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\DependencyInjection\Cache;

use Atoolo\GraphQL\Search\DependencyInjection\Configuration;
use Atoolo\GraphQL\Search\Service\GraphQLOperationManager;
use Atoolo\GraphQL\Search\Service\GraphQLOperationValidator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * @codeCoverageIgnore
 */
class GraphQLOperationValidationCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly GraphQLOperationManager $graphQLOperationManager,
        private readonly GraphQLOperationValidator $graphQLOperationValidator,
        private readonly string $environment,
    ) {}

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        $operations = $this->graphQLOperationManager->getOperations();
        $errorFound = false;
        foreach ($operations as $operation) {
            if (!empty($this->graphQLOperationValidator->validate($operation))) {
                $errorFound = true;
                break;
            }
        }
        if ($errorFound) {
            throw new \RuntimeException(
                sprintf(
                    'GraphQL validation failed! Run "bin/console %s:validate-operations --env=%s" for details.',
                    Configuration::NAME,
                    $this->environment,
                ),
            );
        }
        return [];
    }

    public function isOptional(): bool
    {
        return false;
    }
}
