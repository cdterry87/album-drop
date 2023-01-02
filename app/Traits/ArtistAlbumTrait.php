<?php

namespace App\Traits;

use App\Models\ArtistAlbum;
use Aerni\Spotify\Facades\SpotifyFacade;

trait ArtistAlbumTrait
{
    /**
     * Get artist's albums from Spotify API and save them to the database.
     */
    public function getAndSaveArtistAlbums($artistId, $artistSpotifyId)
    {
        // Get artist albums from Spotify API
        $artistAlbums = SpotifyFacade::artistAlbums($artistSpotifyId)
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

            // Update or create the artist's album
            ArtistAlbum::updateOrCreate([
                'artist_id' => $artistId,
                'spotify_album_id' => $albumId,
            ], [
                'name' => $album['name'],
                'release_date' => $releaseDate,
                'url' => $album['external_urls']['spotify'],
                'image' => $album['images'][0]['url'],
                'type' => $album['type'],
            ]);
        }
    }
}
