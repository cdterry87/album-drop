<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserArtist;

class TrackedArtists extends Component
{
    protected $listeners = ['refreshTrackedArtists' => '$refresh'];

    public $search;

    public function render()
    {
        $results = UserArtist::select('artists.name', 'artists.image', 'artists.url', 'artists.spotify_artist_id')
            ->where('user_id', auth()->id())
            ->join('artists', 'artists.id', '=', 'users_artists.artist_id')
            ->when(strlen($this->search) > 2, function ($query) {
                $query->where('artists.name', 'like', "%{$this->search}%");
            })
            ->orderBy('artists.name')
            ->get();

        return view('livewire.tracked-artists', [
            'results' => $results,
        ]);
    }
}
