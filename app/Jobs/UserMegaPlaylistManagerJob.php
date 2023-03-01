<?php

namespace App\Jobs;

use App\Models\User;
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

class UserMegaPlaylistManagerJob implements ShouldQueue
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
        // Get users that allow us to create a playlist for them and have a valid Spotify playlist id
        $users = User::query()
            ->with('artists')
            ->where('create_mega_playlist', true)
            ->where('spotify_mega_playlist_id', '!=', null)
            ->inRandomOrder()
            ->get();

        foreach ($users as $user) {
            $playlistId = $user->spotify_mega_playlist_id;

            // Verify that the user's playlist still exists on Spotify
            $playlist = SpotifyFacade::playlist($playlistId)->get();

            // If the user's playlist doesn't exist on Spotify, reset their create playlist setting to false
            // The user will need to turn it back on to create a new managed playlist
            if (!$playlist) {
                $user->create_mega_playlist = false;
                $user->spotify_mega_playlist_id = null;
                $user->save();
                continue;
            }

            // Get a list of artist ids for this user
            $userArtistsIds = $user
                ->artists
                ->pluck('id');

            // Get all artist album tracks for the user's artists
            $tracks = ArtistAlbumTrack::select('spotify_track_id')
                ->whereIn('artist_id', $userArtistsIds)
                ->whereNotIn('spotify_track_id', $user->playlistTracks->pluck('spotify_track_id'))
                ->limit(100)
                // Where name doesn't contain "Remix" or "Live"
                ->whereRaw('LOWER(name) NOT LIKE "%remix%"')
                ->whereRaw('LOWER(name) NOT LIKE "%live%"')
                ->inRandomOrder()
                ->pluck('spotify_track_id')
                ->toArray();

            // Add the tracks to the user's playlist
            $api = $this->createSpotifySession($user);
            $api->addPlaylistTracks($playlistId, $tracks);

            // Add the tracks to the user's playlist tracks table so we won't add them again
            foreach ($tracks as $key => $trackId) {
                UserPlaylistTrack::create([
                    'user_id' => $user->id,
                    'spotify_playlist_id' => $playlistId,
                    'spotify_track_id' => $trackId,
                ]);
            }
        }
    }
}
