<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;
use App\Models\ArtistAlbum;
use App\Traits\ArtistAlbumTrait;
use App\Traits\PaginationTrait;

class AlbumTracks extends Component
{
    use ArtistAlbumTrait;
    use PaginationTrait;

    public $albumSpotifyId, $albumTracks, $albumImage, $albumName, $albumUrl, $albumReleaseDate, $artistName;

    public function mount($albumSpotifyId)
    {
        $this->albumSpotifyId = $albumSpotifyId;

        $album = ArtistAlbum::query()
            ->with('artist')
            ->where('spotify_album_id', $albumSpotifyId)
            ->first();

        if ($album) {
            $this->artistName = $album->artist->name;
            $this->albumName = $album->name;
            $this->albumUrl = $album->url;
            $this->albumImage = $album->image;
            $this->albumReleaseDate = $album->release_date;
            $this->albumTracks = $this->getAlbumTracks($albumSpotifyId);
        }
    }

    public function render()
    {
        return view('livewire.album-tracks');
    }
}
