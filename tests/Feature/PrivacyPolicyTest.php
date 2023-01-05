<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    public function test_renders_correctly()
    {
        // Unauthenticated version
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Privacy Policy');
    }
}
