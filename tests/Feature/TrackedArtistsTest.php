<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use Livewire\Livewire;
use App\Models\UserArtist;
use App\Http\Livewire\TrackedArtists;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrackedArtistsTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $this->get(route('tracked-artists'))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly_without_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tracked-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(TrackedArtists::class)
            ->assertSee('Tracked Artists')
            ->assertSeeHtml('id="no-tracked-artists"')
            ->assertDontSeeHtml('id="tracked-artists"');
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

        // Make sure the artists show up on the page
        $this->actingAs($user)
            ->get(route('tracked-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(TrackedArtists::class)
            ->assertSee('Tracked Artists')
            ->assertSee($artist->name)
            ->assertSeeHtml('id="tracked-artists"')
            ->assertDontSeeHtml('id="no-tracked-artists"');
    }

    public function test_search()
    {
        // Create user
        $user = User::factory()->create();

        // Create artists to track
        $artist1 = Artist::factory()->create([
            'name' => 'AAAAA',
        ]);
        $artist2 = Artist::factory()->create([
            'name' => 'BBBBB',
        ]);
        $artist3 = Artist::factory()->create([
            'name' => 'BBBCCC',
        ]);
        $artist4 = Artist::factory()->create([
            'name' => 'CCCCC',
        ]);

        // Add artists to user's tracked artists
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist1->id,
        ]);
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist2->id,
        ]);
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist3->id,
        ]);
        UserArtist::create([
            'user_id' => $user->id,
            'artist_id' => $artist4->id
        ]);

        // Make sure the album shows up on the new releases page
        $this->actingAs($user)
            ->get(route('tracked-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(TrackedArtists::class)
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
