<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserPlaylistManagerJob.php implements ShouldQueue
{
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
        // Keep track of the number of API calls made
        $apiCallsCount = 0;

        // Get that allow users in random order
        $users = User::query()
            ->with('artists')
            ->where('create_playlist', true)
            ->where('spotify_playlist_id', '!=', null)
            ->inRandomOrder()
            ->get();

        foreach ($users as $user) {
            // Verify that the user's playlist still exists on Spotify
            $playlist = SpotifyFacade::playlist($user->spotify_playlist_id)->get();
            if (!$playlist) continue;

            // Get a list of artist ids for this user
            $userArtistsIds = $user
                ->artists
                ->pluck('id');

            // Get all artist album tracks for the user's artists
            $tracks = ArtistAlbumTrack::query()
                ->whereIn('artist_id', $userArtistsIds)
                ->whereNotIn('spotify_track_id', $user->playlistTracks->pluck('spotify_track_id'))
                ->limit(100);

                // Create the string of track ids to submit to the spotify api to add to the playlist

                // api call to add to the user's playlist

                // Limit number of API calls
                $apiCallsCount++;
                if ($apiCallsCount === 100) {
                    // Wait 1 minute for the api rate limit to reset
                    sleep(60);
                    $apiCallsCount = 0;
                }
        }
    }
}
