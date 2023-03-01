<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserArtist;
use App\Traits\SpotifyTrait;
use Illuminate\Bus\Queueable;
use App\Models\ArtistAlbumTrack;
use App\Models\UserPlaylistTrack;
use Illuminate\Queue\SerializesModels;
use Aerni\Spotify\Facades\SpotifyFacade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UserWeeklyPlaylistManagerJob implements ShouldQueue
{
    use SpotifyTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get users that allow Album Drop to create a New Releases playlist for them and have a valid Spotify playlist id
        $users = User::query()
            ->where('create_weekly_playlist', true)
            ->where('spotify_weekly_playlist_id', '!=', null)
            ->inRandomOrder()
            ->get();

        foreach ($users as $user) {
            $playlistId = $user->spotify_weekly_playlist_id;

            // Verify that the user's playlist still exists on Spotify
            $playlist = SpotifyFacade::playlist($playlistId)->get();

            // If the user's playlist doesn't exist on Spotify, reset their create playlist setting to false
            // The user will need to turn it back on for Album Drop to create a new managed playlist
            if (!$playlist) {
                $user->create_weekly_playlist = false;
                $user->spotify_weekly_playlist_id = null;
                $user->save();
                continue;
            } else {
                $api = $this->createSpotifySession($user);

                // Check the user's playlist and remove all tracks
                if ($playlist && $playlist['tracks'] && $playlist['tracks']['items']) {
                    $tracksToRemove = [
                        'tracks' => [],
                    ];

                    foreach ($playlist['tracks']['items'] as $track) {
                        $tracksToRemove['tracks'][]['uri'] = $track['track']['id'];
                    }

                    // Remove the specified tracks
                    if (count($tracksToRemove['tracks']) > 0) {
                        $api->deletePlaylistTracks($playlistId, $tracksToRemove, $playlist['snapshot_id']);
                    }
                }

                // Get user's tracked artists
                $userArtists = UserArtist::select('artist_id')
                    ->where('user_id', $user->id)
                    ->get()
                    ->pluck('artist_id')
                    ->toArray();

                // Get 100 random ArtistAlbumTracks from the user's tracked artists
                $artistAlbumTracks = ArtistAlbumTrack::select('spotify_track_id')
                    ->whereIn('artist_id', $userArtists)
                    // Where name doesn't contain "Remix" or "Live"
                    ->whereRaw('LOWER(name) NOT LIKE "%remix%"')
                    ->whereRaw('LOWER(name) NOT LIKE "%live%"')
                    ->inRandomOrder()
                    ->limit(100)
                    ->get()
                    ->pluck('spotify_track_id')
                    ->toArray();

                // Add the tracks to the user's playlist
                if (count($artistAlbumTracks) > 0) {
                    // Call addPlaylistTracks in increments of 100 to avoid hitting the Spotify API rate limit
                    $chunks = array_chunk($artistAlbumTracks, 100);
                    foreach ($chunks as $chunk) {
                        $api->addPlaylistTracks($playlistId, $chunk);
                    }

                    // Add the tracks to the user's playlist tracks table so we won't add them again
                    foreach ($artistAlbumTracks as $key => $trackId) {
                        UserPlaylistTrack::create([
                            'user_id' => $user->id,
                            'spotify_playlist_id' => $playlistId,
                            'spotify_track_id' => $trackId,
                        ]);
                    }
                }
            }
        }
    }
}
