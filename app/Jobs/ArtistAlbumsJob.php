<?php

namespace App\Jobs;

use App\Models\Artist;
use App\Models\UserAlbum;
use App\Models\UserArtist;
use App\Models\ArtistAlbum;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Aerni\Spotify\Facades\SpotifyFacade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ArtistAlbumsJob implements ShouldQueue
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
            ->select('id', 'spotify_artist_id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Get all albums for each artist
        foreach ($artists as $artist) {
            $artistId = $artist->spotify_artist_id;

            $artistAlbums = SpotifyFacade::artistAlbums($artistId)
                ->includeGroups('album,single')
                ->get();

            // Save each album
            $albums = $artistAlbums['items'];
            foreach ($albums as $album) {
                $albumId = $album['id'];

                // Format release date, if needed
                $releaseDate = $album['release_date'];
                if ($album['release_date_precision'] == 'year') {
                    $releaseDate .= '-01-01';
                }
                if ($album['release_date_precision'] == 'month') {
                    $releaseDate .= '-01';
                }

                // Update or create the album
                $newAlbum = ArtistAlbum::updateOrCreate([
                    'artist_id' => $artist->id,
                    'spotify_album_id' => $albumId,
                ], [
                    'name' => $album['name'],
                    'release_date' => $releaseDate,
                    'url' => $album['external_urls']['spotify'],
                    'image' => $album['images'][0]['url'],
                    'type' => $album['type'],
                ]);

                // Get all users who are tracking this artist
                $usersTrackingArtist = UserArtist::select('user_id')
                    ->join('artists', 'artists.id', '=', 'users_artists.artist_id')
                    ->where('artists.spotify_artist_id', $artistId)
                    ->get();

                // Add the album to the user
                foreach ($usersTrackingArtist as $user) {
                    UserAlbum::updateOrCreate([
                        'user_id' => $user->user_id,
                        'album_id' => $newAlbum->id,
                    ]);
                }
            }
        }
    }
}
