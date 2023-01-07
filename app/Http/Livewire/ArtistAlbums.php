<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;
use App\Models\ArtistAlbum;
use App\Traits\ArtistAlbumTrait;
use App\Traits\PaginationTrait;

class ArtistAlbums extends Component
{
    use ArtistAlbumTrait;
    use PaginationTrait;

    public $artistSpotifyId, $artistId, $artistName;

    public function mount($artistSpotifyId)
    {
        $this->artistSpotifyId = $artistSpotifyId;

        $artist = Artist::select('id', 'name')
            ->where('spotify_artist_id', $artistSpotifyId)
            ->first();

        $this->artistId = $artist->id;
        $this->artistName = $artist->name;
    }

    public function render()
    {
        return view('livewire.artist-albums', [
            'results' => $this->getArtistAlbums()
        ]);
    }

    protected function getArtistAlbums()
    {
        $results = ArtistAlbum::query()
            ->where('artist_id', $this->artistId)
            ->orderBy('release_date', 'desc')
            ->paginate(12);

        // If artist currently has no albums in the system, get them from Spotify and save them to the database
        if ($results->isEmpty()) {
            $this->getAndSaveArtistAlbums($this->artistId, $this->artistSpotifyId);

            // Try again to get artist albums from the database
            $results = ArtistAlbum::query()
                ->where('artist_id', $this->artistId)
                ->orderBy('release_date', 'desc')
                ->paginate(12);
        }

        return $results;
    }
}
