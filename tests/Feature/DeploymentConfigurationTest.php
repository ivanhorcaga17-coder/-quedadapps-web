<?php

namespace Tests\Feature;

use Tests\TestCase;

class DeploymentConfigurationTest extends TestCase
{
    public function test_the_caddyfile_matches_the_expected_railway_configuration(): void
    {
        $expectedCaddyfile = <<<'CADDY'
{
	auto_https off
	servers {
		trusted_proxies static private_ranges
	}
}

:{$PORT:8080} {
	bind 0.0.0.0
	root * /app/public
	encode gzip zstd
	php_server
	log {
		output stdout
		format console
	}
}
CADDY;

        $this->assertSame($expectedCaddyfile."\n", file_get_contents(base_path('Caddyfile')));
    }

    public function test_vite_configuration_points_to_the_public_build_manifest(): void
    {
        $viteConfig = require config_path('vite.php');
        $databaseConfig = require config_path('database.php');

        $this->assertSame('public/build/manifest.json', $viteConfig['manifest']);
        $this->assertSame('build', $viteConfig['build_path']);
        $this->assertFileExists(public_path('index.php'));
        $this->assertArrayHasKey(\PDO::ATTR_TIMEOUT, $databaseConfig['connections']['mysql']['options']);
    }

    public function test_the_homepage_respects_forwarded_proxy_headers(): void
    {
        $response = $this->withServerVariables([
            'HTTP_HOST' => 'internal-container:8080',
            'HTTP_X_FORWARDED_HOST' => 'quedadapps.example.com',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '443',
        ])->get('/');

        $response
            ->assertOk()
            ->assertSee('https://quedadapps.example.com/login', false)
            ->assertDontSee('http://internal-container:8080', false);
    }

    public function test_the_docker_build_uses_php_84_and_recreates_the_storage_link(): void
    {
        $dockerfile = file_get_contents(base_path('Dockerfile'));

        $this->assertStringContainsString('FROM dunglas/frankenphp:php8.4-bookworm', $dockerfile);
        $this->assertStringContainsString('rm -rf public/storage', $dockerfile);
        $this->assertStringContainsString('php artisan storage:link --no-interaction', $dockerfile);
        $this->assertStringContainsString('CMD ["frankenphp", "run", "--config=/app/Caddyfile"]', $dockerfile);
    }

    public function test_the_dockerignore_excludes_the_local_env_file(): void
    {
        $dockerignore = file_get_contents(base_path('.dockerignore'));

        $this->assertStringContainsString('.env', $dockerignore);
    }
}
