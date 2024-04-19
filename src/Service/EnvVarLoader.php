<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Service;

use Symfony\Component\DependencyInjection\EnvVarLoaderInterface;
use Symfony\Component\Dotenv\Dotenv;

class EnvVarLoader implements EnvVarLoaderInterface
{
    private const IES_WEBNODE_SOLR_PORT = '8382';

    private readonly string $baseDir;

    public function __construct(
        string $baseDir = null
    ) {
        $this->baseDir = $baseDir ?? (getcwd() ?: '');
    }

    public function loadEnvVars(): array
    {
        $env = [];

        $resourceRoot = $_SERVER['RESOURCE_ROOT'] ?? '';
        if (empty($resourceRoot)) {
            $resourceRoot = $this->determineResourceRoot();
            if (!empty($resourceRoot)) {
                $env['RESOURCE_ROOT'] = $resourceRoot;
            }
        }

        if (!isset($_SERVER['SOLR_URL']) && !empty($resourceRoot)) {
            $solrUrl = $this->determineSolrUrl($resourceRoot);
            if (!empty($solrUrl)) {
                $url = parse_url($solrUrl);
                $scheme = $url['scheme'] ?? 'http';
                $host = $url['host'] ?? 'localhost';
                $port = (string)(
                    $url['port'] ??
                    (
                        $scheme === 'https' ?
                            '443' :
                            self::IES_WEBNODE_SOLR_PORT
                    )
                );
                $path = $url['path'] ?? '';

                $env['SOLR_SCHEME'] = $scheme;
                $env['SOLR_HOST'] = $host;
                $env['SOLR_PORT'] = $port;
                $env['SOLR_PATH'] = $path;
            }
        }

        return $env;
    }

    private function determineResourceRoot(): string
    {
        /** @var string[] $directories */
        $directories = [
            $this->baseDir
        ];

        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $binDir = dirname($_SERVER['SCRIPT_FILENAME']);
            $appDir = dirname($binDir);
            $hostDir = dirname($appDir);
            $directories[] = $hostDir;
        }

        foreach ($directories as $dir) {
            $realpath = realpath($dir);
            if ($realpath === false) {
                continue;
            }

            if (is_file($realpath . '/resources/context.php')) {
                return $realpath . '/resources';
            }
            if (is_file($realpath . '/context.php')) {
                return $realpath;
            }
            if (is_file($realpath . '/WEB-IES/context.php')) {
                return $realpath;
            }
        }

        return '';
    }

    /**
     * In development environments, no SOLR_URL is set for the bin/console
     * calls. This can be searched for via the determined $resourceRoot for
     * the .env file of the Docker environment. This contains the
     * SERVER_BASE_NAME variable, which can be used to determine the SOLR_URL.
     */
    private function determineSolrUrl(string $resourceRoot): string
    {

        $directories = [
            $resourceRoot . '/../../../..',
            $resourceRoot . '/../../../../..',
        ];

        foreach ($directories as $dir) {
            if (is_file($dir . '/.env')) {
                $dotenv = new Dotenv();
                $dotenv->load($dir . '/.env');
                return 'https://solr-' . $_ENV['SERVER_BASE_NAME'];
            }
        }

        return '';
    }
}
