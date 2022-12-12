<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Aerni\Spotify\Facades\SpotifyFacade;

class SearchArtists extends Component
{
    public $search;
    public $results;
    public $isSearchComplete = false;

    public function render()
    {
        return view('livewire.search-artists');
    }

    public function search()
    {
        // Use the Spotify facade to search for artists
        $results = SpotifyFacade::searchArtists($this->search)
            ->limit(6)
            ->get();

        // Set the results to the artist items
        $this->results = $results['artists']['items'];

        // Then set the search to complete
        $this->isSearchComplete = true;
    }

    public function trackArtist($spotifyId)
    {
        // Use the Spotify facade to get the artist
        $artist = SpotifyFacade::getArtist($spotifyId);

        // Save artist to the artists table
    }
}
