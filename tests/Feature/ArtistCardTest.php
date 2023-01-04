<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use Livewire\Livewire;
use App\Models\UserArtist;
use App\Http\Livewire\Components\ArtistCard;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_correctly()
    {
        Livewire::test(ArtistCard::class, [
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotifyId' => 'spotify:artist:123',
            'hasAlbums' => false,
            'isTracked' => false
        ])
            ->assertSee('Artist Name')
            ->assertSee('image.jpeg')
            ->assertSee('https://example.com')
            ->assertSee('spotify:artist:123')
            ->assertDontSeeHtml('data-icon="album"')
            ->assertDontSeeHtml('data-icon="cancel"')
            ->assertSeeHtml('data-icon="heart"');
    }

    public function test_has_albums()
    {
        Livewire::test(ArtistCard::class, [
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotifyId' => 'spotify:artist:123',
            'hasAlbums' => true,
        ])
            ->assertSee('Artist Name')
            ->assertSee('image.jpeg')
            ->assertSee('https://example.com')
            ->assertSee('spotify:artist:123')
            ->assertSeeHtml('data-icon="album"');
    }

    public function test_determine_is_tracked()
    {
        $user = User::factory()->create();

        // Create an artist
        $artist = Artist::create([
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => 'spotify:artist:123',
        ]);

        Livewire::actingAs($user)
            ->test(ArtistCard::class, [
                'name' => $artist->name,
                'image' => $artist->image,
                'url' => $artist->url,
                'spotifyId' => $artist->spotify_artist_id,
            ])
            ->call('determineIsTracked')
            ->assertSet('isTracked', false)
            ->assertDontSeeHtml('data-icon="cancel"')
            ->assertSeeHtml('data-icon="heart"');

        // Add artist to user's tracked artists
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist->id,
        ]);

        Livewire::actingAs($user)
            ->test(ArtistCard::class, [
                'name' => $artist->name,
                'image' => $artist->image,
                'url' => $artist->url,
                'spotifyId' => $artist->spotify_artist_id,
            ])
            ->call('determineIsTracked')
            ->assertSet('isTracked', true)
            ->assertDontSeeHtml('data-icon="heart"')
            ->assertSeeHtml('data-icon="cancel"');
    }

    public function test_user_can_track_artist()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ArtistCard::class, [
                'name' => 'Artist Name',
                'image' => 'image.jpeg',
                'url' => 'https://example.com',
                'spotifyId' => 'spotify:artist:123',
            ])
            ->assertSeeHtml('data-icon="heart"')
            ->call('trackArtist')
            ->assertSeeHtml('data-icon="cancel"')
            ->assertDontSeeHtml('data-icon="heart"');

        $this->assertDatabaseHas('artists', [
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => 'spotify:artist:123',
        ]);

        $artist = Artist::select('id')->where('spotify_artist_id', 'spotify:artist:123')->first();

        $this->assertDatabaseHas('users_artists', [
            'user_id' => $user->id,
            'artist_id' => $artist->id,
        ]);
    }

    public function test_user_can_untrack_artist()
    {
        $user = User::factory()->create();

        // Create an artist
        $artist = Artist::create([
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => 'spotify:artist:123',
        ]);

        // Add artist to user's tracked artists
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist->id,
        ]);

        Livewire::actingAs($user)
            ->test(ArtistCard::class, [
                'name' => 'Artist Name',
                'image' => 'image.jpeg',
                'url' => 'https://example.com',
                'spotifyId' => 'spotify:artist:123',
            ])
            ->assertSet('isTracked', true)
            ->assertSeeHtml('data-icon="cancel"')
            ->call('untrackArtist')
            ->assertSeeHtml('data-icon="heart"')
            ->assertDontSeeHtml('data-icon="cancel"');

        // Artist should remain in the artists table
        $this->assertDatabaseHas('artists', [
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => 'spotify:artist:123',
        ]);

        // User should no longer be tracking the artist
        $this->assertDatabaseMissing('users_artists', [
            'user_id' => $user->id,
            'artist_id' => $artist->id,
        ]);
    }
}
