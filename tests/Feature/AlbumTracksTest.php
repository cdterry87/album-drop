<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use App\Models\ArtistAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumTracksTest extends TestCase
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
        $album = ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'Album Name',
            'image' => 'album-name.jpeg',
            'url' => 'https://example.com/album-name',
            'spotify_album_id' => 'spotify:album:123',
        ]);

        $this->get(route('album-tracks', [
            'albumSpotifyId' => $album->spotify_album_id
        ]))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly()
    {
        $user = User::factory()->create();

        $artist = Artist::create([
            'name' => 'AFI',
            'image' => 'image.jpeg',
            'url' => 'https://example.com',
            'spotify_artist_id' => '19I4tYiChJoxEO5EuviXpz',
        ]);

        // Add album for artist
        $album = ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'Album Name',
            'image' => 'album-name.jpeg',
            'url' => 'https://example.com/album-name',
            'spotify_album_id' => '1eIzVBHA5NvX0wo2nLACew',
            'release_date' => now()
        ]);

        $this->actingAs($user)
            ->get(route('album-tracks', [
                'albumSpotifyId' => $album->spotify_album_id
            ]))
            ->assertStatus(200)
            ->assertSee($album->name)
            ->assertSee($artist->name)
            ->assertSee(getFormalDate($album->release_date))
            ->assertSee($album->url)
            ->assertSee($album->image)
            ->assertSee('album-tracks');
    }
}
