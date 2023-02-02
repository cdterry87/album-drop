<?php

namespace App\Jobs;

use App\Models\ArtistAlbum;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Aerni\Spotify\Facades\SpotifyFacade;
use App\Models\ArtistAlbumTrack;

class ArtistAlbumTracksJob implements ShouldQueue
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
        // Get all albums that haven't been imported yet
        $albums = ArtistAlbum::distinct()
            ->select('id', 'artist_id', 'spotify_album_id')
            ->where('imported', false)
            ->inRandomOrder()
            ->get();

        foreach ($albums as $album) {
            $albumId = $album->id;
            $artistId = $album->artist_id;
            $albumSpotifyId = $album->spotify_album_id;

            // Get album tracks from spotify
            $albumTracks = SpotifyFacade::albumTracks($albumSpotifyId)->get();

            // Add tracks to database
            $tracks = $albumTracks['items'];
            foreach ($tracks as $track) {
                $trackId = $track['id'];
                $trackName = $track['name'];
                $trackNumber = $track['track_number'];
                $trackDuration = $track['duration_ms'];

                ArtistAlbumTrack::updateOrCreate([
                    'artist_id' => $artistId,
                    'album_id' => $albumId,
                ], [
                    'spotify_track_id' => $trackId,
                    'name' => $trackName,
                    'track_number' => $trackNumber,
                    'duration_ms' => $trackDuration,
                ]);
            }

            // Mark album as imported
            $album->imported = true;
            $album->save();
        }
    }
}
