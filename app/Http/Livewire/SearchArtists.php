<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Aerni\Spotify\Facades\SpotifyFacade;

class SearchArtists extends Component
{
    public $search;
    public $results;
    public $isSearchComplete = false;

    public $messages = [
        'search.required' => 'You must enter a search term.',
        'search.min' => 'Your search term must be at least 2 characters.',
    ];

    public function render()
    {
        return view('livewire.search-artists');
    }

    public function search()
    {
        $this->validate([
            'search' => 'required|min:2',
        ]);

        // Use the Spotify facade to search for artists
        $results = SpotifyFacade::searchArtists($this->search)
            ->limit(30)
            ->get();

        // Set the results to the artist items
        $this->results = $results['artists']['items'];

        // Then set the search to complete
        $this->isSearchComplete = true;
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->results = [];
        $this->isSearchComplete = false;
    }
}
