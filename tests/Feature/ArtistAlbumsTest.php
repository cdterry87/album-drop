<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use App\Models\ArtistAlbum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistAlbumsTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $artist = Artist::create([
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => 'spotify:artist:123',
        ]);

        // Add albums for artist
        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'Album Name',
            'image' => 'album-name.jpeg',
            'url' => 'https://example.com/album-name',
            'spotify_album_id' => 'spotify:album:123',
        ]);
        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'Another Album',
            'image' => 'another-album.jpeg',
            'url' => 'https://example.com/another-album',
            'spotify_album_id' => 'spotify:album:456',
        ]);

        $this->get(route('artist-albums', [
            'artistSpotifyId' => $artist->spotify_artist_id
        ]))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly()
    {
        $user = User::factory()->create();

        $artist = Artist::create([
            'name' => 'Artist Name',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => 'spotify:artist:123',
        ]);

        // Add albums for artist
        $album1 = ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'Album Name',
            'image' => 'album-name.jpeg',
            'url' => 'https://example.com/album-name',
            'spotify_album_id' => 'spotify:album:123',
        ]);
        $album2 = ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'Another Album',
            'image' => 'another-album.jpeg',
            'url' => 'https://example.com/another-album',
            'spotify_album_id' => 'spotify:album:456',
        ]);

        $this->actingAs($user)
            ->get(route('artist-albums', [
                'artistSpotifyId' => $artist->spotify_artist_id
            ]))
            ->assertStatus(200)
            ->assertSee('Artist Name')
            ->assertSeeLivewire('components.album-card')
            ->assertSee($album1->name)
            ->assertSee($album1->image)
            ->assertSee($album1->url)
            ->assertSee($album2->name)
            ->assertSee($album2->image)
            ->assertSee($album2->url);
    }
}
