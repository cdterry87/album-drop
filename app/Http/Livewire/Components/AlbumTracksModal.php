<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Traits\ArtistAlbumTrait;

class AlbumTracksModal extends Component
{
    use ArtistAlbumTrait;

    protected $listeners = ['viewTracks'];

    public $isModalShown = false;
    public $album, $releaseDate, $results;

    public function render()
    {
        return view('livewire.components.album-tracks-modal');
    }

    public function viewTracks($spotifyId, $album, $releaseDate)
    {
        $this->isModalShown = true;

        $this->album = $album;
        $this->releaseDate = $releaseDate;

        $results = $this->getAlbumTracks($spotifyId);
        $this->results = $results['items'];
    }

    public function closeModal()
    {
        $this->isModalShown = false;
        $this->results = null;
    }
}
