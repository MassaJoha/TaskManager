<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_requires_authentication()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }
}
