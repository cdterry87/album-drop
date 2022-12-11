<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Aerni\Spotify\Facades\SpotifyFacade as Spotify;

class Dashboard extends Component
{
    public function render()
    {
        $results = Spotify::searchArtists('a day to remember')->get();

        return view('livewire.dashboard', [
            'results' => $results,
        ]);
    }
}
