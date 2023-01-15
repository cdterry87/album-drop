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

            // Make sure album image is set
            $albumImage = null;
            if (
                is_array($album['images'])
                && count($album['images']) > 0
                && is_array($album['images'][0])
                && isset($album['images'][0]['url'])
            ) {
                $albumImage = $album['images'][0]['url'];
            }

            // Update or create the artist's album
            ArtistAlbum::updateOrCreate([
                'artist_id' => $artistId,
                'spotify_album_id' => $albumId,
            ], [
                'name' => $album['name'],
                'release_date' => $releaseDate,
                'url' => $album['external_urls']['spotify'],
                'image' => $albumImage,
                'type' => $album['type'],
            ]);
        }
    }

    /**
     * Get album tracks
     */
    public function getAlbumTracks($spotifyId)
    {
        return SpotifyFacade::albumTracks($spotifyId)->get();
    }
}
