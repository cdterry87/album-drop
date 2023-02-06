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

        // Connect to Spotify API as the current user
        $api = $this->createSpotifySession($user);

        // Sync artists with Spotify
        $syncedToSpotify = $this->syncArtistsToSpotify($api, $user);
        $syncedFromSpotify = $this->syncArtistsFromSpotify($api, $user);

        // If we synced anything, redirect with a success message
        if ($syncedToSpotify || $syncedFromSpotify) {
            return redirect()
                ->route('tracked-artists')
                ->with('sync-message', 'Your artists have been successfully synced with Spotify!');
        }

        // Otherwise redirect with a warning message
        return redirect()
            ->route('tracked-artists')
            ->with('no-sync-message', 'No artists were synced with Spotify. Track artists or follow more artists on Spotify to sync them.');
    }

    protected function syncArtistsToSpotify($api, $user)
    {
        // Get user's tracked artists from database
        $trackedArtists = UserArtist::with('artist')->where('user_id', $user->id)->get();
        $trackedArtistsIds = [];
        $trackedArtistsCount = 0;
        foreach ($trackedArtists as $artist) {
            $trackedArtistsIds[] = $artist->artist->spotify_artist_id;

            $trackedArtistsCount++;

            // If we have 50 artists, we need to make a request to Spotify to follow them
            if (count($trackedArtistsIds) == 50) {
                $api->followArtistsOrUsers('artist', $trackedArtistsIds);
                $trackedArtistsIds = [];
            }
        }

        // If we have any artists left, make a request to Spotify to follow them
        if (count($trackedArtistsIds) > 0) {
            $api->followArtistsOrUsers('artist', $trackedArtistsIds);
            $trackedArtistsIds = [];
        }

        if ($trackedArtistsCount > 0) {
            return true;
        }
        return false;
    }

    protected function syncArtistsFromSpotify($api, $user)
    {
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

            return true;
        }
        return false;
    }
}
