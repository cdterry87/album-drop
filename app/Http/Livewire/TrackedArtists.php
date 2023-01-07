<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserArtist;
use App\Traits\PaginationTrait;

class TrackedArtists extends Component
{
    use PaginationTrait;

    protected $listeners = ['refreshTrackedArtists' => '$refresh'];

    public $search;
    public $filter_show = 12;

    public function render()
    {
        // Get user's tracked artists
        $results = UserArtist::select('artists.name', 'artists.image', 'artists.url', 'artists.spotify_artist_id')
            ->where('user_id', auth()->id())
            ->join('artists', 'artists.id', '=', 'users_artists.artist_id')
            ->when(strlen($this->search) > 2, function ($query) {
                $query->where('artists.name', 'like', "%{$this->search}%");
            })
            ->orderBy('artists.name')
            ->paginate($this->filter_show);

        return view('livewire.tracked-artists', [
            'results' => $results,
        ]);
    }
}
