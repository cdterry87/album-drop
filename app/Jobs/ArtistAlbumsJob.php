<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Traits\ArtistAlbumTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ArtistAlbumsJob implements ShouldQueue
{
    use ArtistAlbumTrait;
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
            $artistId = $artist->id;
            $artistSpotifyId = $artist->spotify_artist_id;

            // Get artist albums from spotify and save them to the database
            $this->getAndSaveArtistAlbums($artistId, $artistSpotifyId);
        }
    }
}
