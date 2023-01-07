<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpotifyLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_spotify_login_redirect()
    {
        $this->get(route('login.spotify'))
            ->assertStatus(302)
            ->assertRedirectContains('https://accounts.spotify.com/authorize');
    }

    /**
     * @todo
     * Mock Socialite for Spotify to create a fake user object
     * Assert that a user gets created
     * Assert that the user gets redirected to the home page
     */
    public function test_spotify_login_callback()
    {
        $this->assertTrue(true);
    }
}
