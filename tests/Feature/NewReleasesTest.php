<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use Livewire\Livewire;
use App\Models\UserArtist;
use App\Models\ArtistAlbum;
use App\Http\Livewire\NewReleases;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewReleasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $this->get(route('new-releases'))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly_without_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('new-releases'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(NewReleases::class)
            ->assertSee('New Releases')
            ->assertSeeHtml('id="no-new-releases"')
            ->assertDontSeeHtml('id="new-releases"');
    }

    public function test_renders_correctly_with_data()
    {
        // Create user
        $user = User::factory()->create();

        // Create artist
        $artist = Artist::factory()->create();

        // Add artist to user's tracked artists
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist->id,
        ]);

        // Create a new released album for the artist
        $album = ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'New Release 1',
            'release_date' => now()->subDays(1),
        ]);

        // Make sure the album shows up on the new releases page
        $this->actingAs($user)
            ->get(route('new-releases'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(NewReleases::class)
            ->assertSee('New Releases')
            ->assertSee($album->name)
            ->assertSeeHtml('id="new-releases"')
            ->assertDontSeeHtml('id="no-new-releases"');
    }

    public function test_filter_days()
    {
        // Create user
        $user = User::factory()->create();

        // Create artist
        $artist = Artist::factory()->create();

        // Add artist to user's tracked artists
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist->id,
        ]);

        // Create several new releases
        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'AAAAA',
            'release_date' => now()->subDays(20),
        ]);

        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'BBBBB',
            'release_date' => now()->subDays(50),
        ]);

        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'CCCCC',
            'release_date' => now()->subDays(150),
        ]);

        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'DDDDD',
            'release_date' => now()->subDays(300),
        ]);

        ArtistAlbum::create([
            'artist_id' => $artist->id,
            'name' => 'EEEEE',
            'release_date' => now()->subDays(500),
        ]);

        // Verify that the number of results matches when the filters change
        Livewire::actingAs($user)
            ->test(NewReleases::class)
            ->set('filter_days', 30)
            ->assertViewHas('results', function ($results) {
                return $results->count() === 1;
            })
            ->set('filter_days', 90)
            ->assertViewHas('results', function ($results) {
                return $results->count() === 2;
            })
            ->set('filter_days', 180)
            ->assertViewHas('results', function ($results) {
                return $results->count() === 3;
            })
            ->set('filter_days', 365)
            ->assertViewHas('results', function ($results) {
                return $results->count() === 4;
            })
            ->set('filter_days', '')
            ->assertViewHas('results', function ($results) {
                return $results->count() === 5;
            });
    }
}
