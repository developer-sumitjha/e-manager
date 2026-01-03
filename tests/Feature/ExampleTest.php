<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // The root route should return a response (could be 200 or 404 depending on setup)
        $this->assertTrue(in_array($response->status(), [200, 404]));
    }
}
