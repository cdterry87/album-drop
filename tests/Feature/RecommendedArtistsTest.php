<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use Livewire\Livewire;
use App\Models\UserArtist;
use App\Models\ArtistRelatedArtist;
use App\Http\Livewire\RecommendedArtists;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecommendedArtistsTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $this->get(route('recommended-artists'))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly_without_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('recommended-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(RecommendedArtists::class)
            ->assertSee('Recommended Artists')
            ->assertSeeHtml('id="no-recommended-artists"')
            ->assertDontSeeHtml('id="recommended-artists"');
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

        // Create related artists
        $recommendedArtist = ArtistRelatedArtist::factory()->create([
            'artist_id' => $artist->id,
            'name' => 'AAAAA',
        ]);

        // Make sure the recommended artists show up on the page
        $this->actingAs($user)
            ->get(route('recommended-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(RecommendedArtists::class)
            ->assertSee('Recommended Artists')
            ->assertSee($recommendedArtist->name)
            ->assertSeeHtml('id="recommended-artists"')
            ->assertDontSeeHtml('id="no-recommended-artists"');
    }

    public function test_search()
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

        // Create related artists
        ArtistRelatedArtist::factory()->create([
            'artist_id' => $artist->id,
            'name' => 'AAAAA',
        ]);
        ArtistRelatedArtist::factory()->create([
            'artist_id' => $artist->id,
            'name' => 'BBBBB',
        ]);
        ArtistRelatedArtist::factory()->create([
            'artist_id' => $artist->id,
            'name' => 'BBBCCC',
        ]);
        ArtistRelatedArtist::factory()->create([
            'artist_id' => $artist->id,
            'name' => 'CCCCC',
        ]);

        // Make sure the album shows up on the recommended artists show up on the page
        $this->actingAs($user)
            ->get(route('recommended-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(RecommendedArtists::class)
            ->assertViewHas('results', function ($results) {
                return $results->count() === 4;
            })
            ->set('search', 'AAA')
            ->assertViewHas('results', function ($results) {
                return $results->count() === 1;
            })
            ->set('search', 'BBB')
            ->assertViewHas('results', function ($results) {
                return $results->count() === 2;
            })
            ->set('search', '')
            ->assertViewHas('results', function ($results) {
                return $results->count() === 4;
            });
    }
}
