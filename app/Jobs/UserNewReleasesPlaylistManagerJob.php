<?php

namespace App\Jobs;

use App\Models\User;
use App\Traits\SpotifyTrait;
use App\Models\UserAlbumDrop;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\UserPlaylistTrack;
use Illuminate\Queue\SerializesModels;
use Aerni\Spotify\Facades\SpotifyFacade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UserNewReleasesPlaylistManagerJob implements ShouldQueue
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
            ->where('create_new_releases_playlist', true)
            ->where('spotify_new_releases_playlist_id', '!=', null)
            ->inRandomOrder()
            ->get();

        foreach ($users as $user) {
            $playlistId = $user->spotify_new_releases_playlist_id;

            // Verify that the user's playlist still exists on Spotify
            $playlist = SpotifyFacade::playlist($playlistId)->get();

            // If the user's playlist doesn't exist on Spotify, reset their create playlist setting to false
            // The user will need to turn it back on for Album Drop to create a new managed playlist
            if (!$playlist) {
                $user->create_new_releases_playlist = false;
                $user->spotify_new_releases_playlist_id = null;
                $user->save();
                continue;
            } else {
                $api = $this->createSpotifySession($user);

                // Check the user's playlist and remove any tracks that the release date is older than 30 days
                if ($playlist && $playlist['tracks'] && $playlist['tracks']['items']) {
                    $tracksToRemove = [
                        'tracks' => [],
                    ];

                    // Check for tracks that are older than 30 days
                    foreach ($playlist['tracks']['items'] as $track) {
                        $releaseDate = $track['track']['album']['release_date'];

                        // Format incomplete dates
                        if (strlen($releaseDate) === 4) {
                            $releaseDate = $releaseDate . '-01-01';
                        } elseif (strlen($releaseDate) === 7) {
                            $releaseDate = $releaseDate . '-01';
                        }

                        // Check if release date is NOT within the past 30 days
                        $releaseDate = Carbon::parse($releaseDate);
                        $now = Carbon::now();
                        $diff = $now->diffInDays($releaseDate);
                        if ($diff > 30) {
                            $tracksToRemove['tracks'][]['uri'] = $track['track']['id'];
                        }
                    }

                    // Remove the specified tracks
                    if (count($tracksToRemove['tracks']) > 0) {
                        $api->deletePlaylistTracks($playlistId, $tracksToRemove, $playlist['snapshot_id']);
                    }
                }

                // Get album drops for this user from the past 30 days
                $albumDrops = UserAlbumDrop::query()
                    ->with('album', 'album.tracks')
                    ->where('user_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(30))
                    ->get();

                // If user has any album drops in the past 30 days, add them to the playlist
                if ($albumDrops) {
                    $tracksToAdd = [];

                    // Get the tracks from the album drops so they can be added to the playlist
                    foreach ($albumDrops as $albumDrop) {
                        foreach ($albumDrop->album->tracks as $track) {
                            // If this track is already in the playlist, skip it
                            $trackExists = UserPlaylistTrack::query()
                                ->where('user_id', $user->id)
                                ->where('spotify_playlist_id', $playlistId)
                                ->where('spotify_track_id', $track->spotify_track_id)
                                ->first();
                            if ($trackExists) continue;

                            $tracksToAdd[] = $track->spotify_track_id;
                        }
                    }

                    // Add the specified tracks
                    if (count($tracksToAdd) > 0) {
                        $api->addPlaylistTracks($playlistId, $tracksToAdd);

                        // Add the tracks to the user's playlist tracks table so we won't add them again
                        foreach ($tracksToAdd as $key => $trackId) {
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
}
