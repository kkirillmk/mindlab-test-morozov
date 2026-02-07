<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_api_info_endpoint_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'MindLab API',
                'version' => '1.0.0',
            ]);
    }
}
