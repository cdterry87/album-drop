<?php

namespace App\Http\Livewire\Components;

use App\Traits\ArtistAlbumTrait;
use Livewire\Component;

class AlbumCard extends Component
{
    use ArtistAlbumTrait;

    public $name, $image, $artist, $url, $releaseDate, $spotifyId;

    public function render()
    {
        return view('livewire.components.album-card');
    }

    public function viewTracks()
    {
        $this->emit('viewTracks', $this->spotifyId, $this->name, $this->releaseDate);
    }
}
