<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class AlbumCard extends Component
{
    public $name, $image, $artist, $url, $releaseDate;

    public function render()
    {
        return view('livewire.components.album-card');
    }
}
