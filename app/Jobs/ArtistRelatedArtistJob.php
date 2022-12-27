<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use App\Models\ArtistRelatedArtist;
use Illuminate\Queue\SerializesModels;
use Aerni\Spotify\Facades\SpotifyFacade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ArtistRelatedArtistJob implements ShouldQueue
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
        // Get all artists in the system that have no related artists
        $artists = Artist::select('id', 'spotify_artist_id')
            ->whereDoesntHave('relatedArtists')
            ->get();

        // Loop over all the artists and get related artists
        foreach ($artists as $artist) {
            $related = SpotifyFacade::artistRelatedArtists($artist->spotify_artist_id)->get();

            $relatedArtists = $related['artists'];
            foreach ($relatedArtists as $relatedArtist) {
                ArtistRelatedArtist::updateOrCreate([
                    'artist_id' => $artist->id,
                    'spotify_artist_id' => $relatedArtist['id'],
                ], [
                    'name' => $relatedArtist['name'],
                    'image' => $relatedArtist['images'][0]['url'],
                    'url' => $relatedArtist['external_urls']['spotify'],
                ]);
            }
        }
    }
}
