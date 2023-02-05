<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;
use App\Models\UserArtist;
use App\Traits\SpotifyTrait;

class Header extends Component
{
    use SpotifyTrait;

    public function render()
    {
        return view('livewire.header');
    }

    public function syncSpotifyArtists()
    {
        $user = auth()->user();

        $api = $this->createSpotifySession($user);

        $userFollowedArtists = $api->getUserFollowedArtists();

        if ($userFollowedArtists) {
            $artists = $userFollowedArtists->artists->items;

            foreach ($artists as $artist) {
                // If artist doesn't exist in the database, create it
                $artistToTrack = Artist::firstOrCreate([
                    'spotify_artist_id' => $artist->id,
                ], [
                    'name' => $artist->name,
                    'url' => $artist->href,
                    'image' => $artist->images[0]->url,
                ]);

                // If artist doesn't exist in the user's artists, create it
                UserArtist::firstOrCreate([
                    'user_id' => $user->id,
                    'artist_id' => $artistToTrack->id,
                ]);
            }

            return redirect()
                ->route('tracked-artists')
                ->with('sync-message', 'Your followed artists were synced successfully from Spotify.');
        }

        return redirect()
            ->route('tracked-artists')
            ->with('no-sync-message', 'No followed artists wwere found from your Spotify account.');
    }
}
