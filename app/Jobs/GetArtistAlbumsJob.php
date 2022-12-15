<?php

namespace App\Jobs;

use App\Models\Album;
use App\Models\Artist;
use App\Models\UserAlbum;
use App\Models\UserArtist;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Aerni\Spotify\Facades\SpotifyFacade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GetArtistAlbumsJob implements ShouldQueue
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
        // Get all (distinct) artists in the system
        $artists = Artist::distinct()
            ->select('spotify_artist_id')
            ->orderBy('name', 'asc')
            ->get();

        // Get all albums for each artist
        foreach ($artists as $artist) {
            $artistId = $artist->artist_id;

            $artistAlbums = SpotifyFacade::artistAlbums($artistId)
                ->includeGroups('album,single')
                ->get();

            // Save each album
            $albums = $artistAlbums['items'];
            foreach ($albums as $album) {
                $albumId = $album['id'];

                // Update or create the album
                $album = Album::updateOrCreate([
                    'spotify_album_id' => $albumId,
                    'spotify_artist_id' => $artistId,
                ], [
                    'name' => $album['name'],
                    'release_date' => $album['release_date'],
                    'url' => $album['external_urls']['spotify'],
                    'image' => $album['images'][0]['url'],
                    'type' => $album['type'],
                ]);

                // Get all users who are tracking this artist
                $usersTrackingArtist = UserArtist::select('user_id')
                    ->where('spotify_artist_id', $artistId)
                    ->with('users')
                    ->get();

                // Add the album to the user
                foreach ($usersTrackingArtist as $user) {
                    UserAlbum::updateOrCreate([
                        'user_id' => $user->user_id,
                        'album_id' => $album->id,
                    ]);
                }
            }
        }
    }
}
