<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeTest extends TestCase
{
    public function test_renders_correctly()
    {
        // Unauthenticated version
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Get Started')
            ->assertSee('Spotify Logo')
            ->assertSee('Privacy Policy')
            ->assertSee('Log in')
            ->assertSee('Register');

        // Authenticated version
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get('/')
            ->assertStatus(200)
            ->assertSee('Get Started')
            ->assertSee('Spotify Logo')
            ->assertSee('Privacy Policy')
            ->assertSee('Logout')
            ->assertSee('Home');
    }
}
