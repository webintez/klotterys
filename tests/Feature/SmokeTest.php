<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Smoke test public endpoints.
     */
    public function test_frontend_routes_are_accessible(): void
    {
        $urls = [
            '/',
            '/about',
            '/contact',
            '/buy-tickets',
            '/track-order',
            '/results',
            '/admin/login',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}
