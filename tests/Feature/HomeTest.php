<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Artist;
use Livewire\Livewire;
use App\Models\UserArtist;
use App\Http\Livewire\Home;
use App\Models\ArtistAlbum;
use App\Models\ArtistRelatedArtist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly_without_results()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(Home::class)
            ->assertSeeHtml('Artists You\'re Tracking')
            ->assertSee('New Releases')
            ->assertSee('Recommended Artists')
            ->assertSee('Latest Tracked Artists')
            ->assertSee('Latest New Releases')
            ->assertSee('Recommended For You')
            ->assertSeeHtml('id="no-tracked-artists"')
            ->assertSeeHtml('id="no-new-releases"')
            ->assertSeeHtml('id="no-recommended-artists"');
    }

    public function test_renders_correctly_with_results()
    {
        $user = User::factory()->create();

        // Create albums and their artists
        $oldAlbums = ArtistAlbum::factory(5)->create();
        $newReleaseAlbums = ArtistAlbum::factory(2)->create([
            'release_date' => now()->subDays(1),
        ]);

        // Combine old and new albums and loop through them to add each album's artist to the user's tracked artists
        $allAlbums = $oldAlbums->merge($newReleaseAlbums);
        foreach ($allAlbums as $album) {
            $artist = $album->artist;

            // Add artist to user's tracked artists
            UserArtist::create([
                'user_id' => $user->id,
                'artist_id' => $artist->id,
            ]);

            // Add some random related artists to the user's tracked artist so they show up on the recommended artists list
            ArtistRelatedArtist::factory()->create([
                'artist_id' => $artist->id,
            ]);
        }

        $this->actingAs($user)
            ->get(route('home'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(Home::class)
            ->assertSeeHtml('Artists You\'re Tracking')
            ->assertSee('New Releases')
            ->assertSee('Recommended Artists')
            ->assertSee('Latest Tracked Artists')
            ->assertSee('Latest New Releases')
            ->assertSee('Recommended For You')
            ->assertSeeHtml('id="latest-tracked-artists"')
            ->assertSeeHtml('id="latest-new-releases"')
            ->assertSeeHtml('id="recommended-artists"');
    }

    public function test_notification_displays_correctly_and_enables_notifications()
    {
        // Not subscribed
        $user = User::factory()->create([
            'subscribed' => false
        ]);

        Livewire::actingAs($user)
            ->test(Home::class)
            ->assertSet('isUserSubscribed', false)
            ->assertSeeHtml('id="not-subscribed--alert"')
            ->assertSee('Yes, enable notifications!')
            ->call('subscribe');

        // After calling the subscribe method, the user should be subscribed
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'subscribed' => true,
        ]);

        // Subscribed
        $user2 = User::factory()->create([
            'subscribed' => true
        ]);

        // View the page as a subscribed user
        Livewire::actingAs($user2)
            ->test(Home::class)
            ->assertSet('isUserSubscribed', true)
            ->assertSeeHtml('id="subscribed--alert"');
    }
}
