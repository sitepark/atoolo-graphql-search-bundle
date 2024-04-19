<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Service;

use Atoolo\GraphQL\Search\Service\EnvVarLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EnvVarLoader::class)]
class EnvVarLoaderTest extends TestCase
{
    private string $baseDir = __DIR__ .
        '/../resources/Service/EnvVarLoader';

    private string $scriptFileNameBackup;

    public function setUp(): void
    {
        $this->scriptFileNameBackup = $_SERVER['SCRIPT_FILENAME'] ?? null;
    }
    public function tearDown(): void
    {
        unset($_SERVER['RESOURCE_ROOT']);
        unset($_SERVER['SOLR_URL']);
        $_SERVER['SCRIPT_FILENAME'] = $this->scriptFileNameBackup;
    }

    public function testLoadVarsWithExistsResourceRoot(): void
    {
        $_SERVER['RESOURCE_ROOT'] = 'test';
        $loader = new EnvVarLoader();
        $env = $loader->loadEnvVars();
        $this->assertFalse(
            isset($env['RESOURCE_ROOT']),
            'RESOURCE_ROOT should no set'
        );
    }

    public function testLoadVarsWithExistsSolrUrl(): void
    {
        $_SERVER['SOLR_URL'] = 'test';
        $loader = new EnvVarLoader();
        $env = $loader->loadEnvVars();
        $this->assertFalse(
            isset($env['SOLR_URL']),
            'RESOURCE_ROOT should no set'
        );
    }

    public function testDetermineResourceWithInvalidDir(): void
    {
        $loader = new EnvVarLoader('/invalid-dir');
        $env = $loader->loadEnvVars();
        $this->assertFalse(
            isset($env['RESOURCE_ROOT']),
            'RESOURCE_ROOT should no set'
        );
    }

    public function testDetermineResourceViaScriptFilename(): void
    {
        $_SERVER['SCRIPT_FILENAME'] =
            $this->baseDir . '/hostDir/app/bin/console';
        $loader = new EnvVarLoader('/tmp');
        $env = $loader->loadEnvVars();
        $this->assertEquals(
            $this->baseDir . '/hostDir/resources',
            isset($env['RESOURCE_ROOT']),
            'unexpected RESOURCE_ROOT'
        );
    }

    public function testDetermineResourceRootInHostDir(): void
    {
        $loader = new EnvVarLoader($this->baseDir . '/hostDir');
        $env = $loader->loadEnvVars();
        $this->assertEquals(
            $this->baseDir . '/hostDir/resources',
            isset($env['RESOURCE_ROOT']),
            'unexpected RESOURCE_ROOT'
        );
    }

    public function testDetermineResourceRootInResourceDir(): void
    {
        $loader = new EnvVarLoader($this->baseDir . '/hostDir/resources');
        $env = $loader->loadEnvVars();
        $this->assertEquals(
            $this->baseDir . '/hostDir/resources',
            isset($env['RESOURCE_ROOT']),
            'unexpected RESOURCE_ROOT'
        );
    }

    public function testDetermineResourceRootWithDocumentRootLayout(): void
    {
        $loader = new EnvVarLoader($this->baseDir . '/documentRootLayout');
        $env = $loader->loadEnvVars();
        $this->assertEquals(
            $this->baseDir . '/documentRootLayout',
            isset($env['RESOURCE_ROOT']),
            'unexpected RESOURCE_ROOT'
        );
    }

    public function testDetermineSolrUrlForDocker(): void
    {
        $hostDir = $this->baseDir .
            '/ies-env/data/publications/example.com/www';

        $loader = new EnvVarLoader($hostDir);
        $env = $loader->loadEnvVars();
        unset($env['RESOURCE_ROOT']);

        $this->assertEquals(
            [
                'SOLR_HOST' =>  'solr-test.example.com',
                'SOLR_SCHEME' => 'https',
                'SOLR_PORT' => '443',
                'SOLR_PATH' => '',
            ],
            $env,
            'unexpected env'
        );
    }
}
