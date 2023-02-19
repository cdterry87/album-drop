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
        // Keep track of the number of iterations we've made
        $iterations = 0;

        // Keep track of the last artist retrieved from Spotify so it can be used to get the next page of artists
        $lastArtistRetreivedId = null;

        // Determines whether the user still has more artists to sync
        $userHasFollowedArtists = true;

        while ($userHasFollowedArtists) {
            // Get the user's followed artists from Spotify
            $userFollowedArtists = $api->getUserFollowedArtists([
                'limit' => 50,
                'after' => $lastArtistRetreivedId,
            ]);

            if ($userFollowedArtists) {
                // Get the artists from the response
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

                    // Save the last artist id that was retrieved
                    $lastArtistRetreivedId = $artist->id;
                }

                // Take a break in between requests to Spotify
                sleep(2);
            }

            // Increment counter
            $iterations++;

            // The system can only pull 50 at a time. If there are less than 50, we know we've reached the end.
            // If we've gone through 100 iterations, we should stop to prevent an infinite loop.
            if (count($artists) < 50 || $iterations  === 120) {
                $userHasFollowedArtists = false;
                return true;
            }
        }
    }
}
