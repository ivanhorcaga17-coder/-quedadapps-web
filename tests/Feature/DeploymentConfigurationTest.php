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

        $this->assertSame('public/build/manifest.json', $viteConfig['manifest']);
        $this->assertSame('build', $viteConfig['build_path']);
        $this->assertFileExists(public_path('index.php'));
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
}
