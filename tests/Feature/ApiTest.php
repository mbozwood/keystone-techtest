<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_api_returns_200(): void
    {
        $response = $this->getJson('/api/links');
        $response->assertStatus(200);
    }
}
