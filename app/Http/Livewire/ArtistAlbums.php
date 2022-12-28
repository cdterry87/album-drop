<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;
use App\Models\ArtistAlbum;
use Livewire\WithPagination;

class ArtistAlbums extends Component
{
    use WithPagination;

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
        $results = ArtistAlbum::query()
            ->where('artist_id', $this->artistId)
            ->orderBy('release_date', 'desc')
            ->paginate(12);

        // @todo - if no results are found, call the spotify API to get artist's albums

        return view('livewire.artist-albums', [
            'results' => $results
        ]);
    }
}
