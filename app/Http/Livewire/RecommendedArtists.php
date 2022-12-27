<?php

namespace App\Http\Livewire;

use Aerni\Spotify\Facades\SpotifyFacade;
use Aerni\Spotify\Facades\SpotifySeedFacade;
use Livewire\Component;

class RecommendedArtists extends Component
{
    public function render()
    {
        return view('livewire.recommended-artists');
    }
}
