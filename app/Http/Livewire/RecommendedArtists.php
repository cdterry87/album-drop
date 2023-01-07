<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserArtist;
use App\Models\ArtistRelatedArtist;
use Livewire\WithPagination;

class RecommendedArtists extends Component
{
    use WithPagination;

    protected $listeners = ['refreshTrackedArtists' => '$refresh'];

    public $search;
    public $filter_show = 12;

    public function render()
    {
        // Get user's artists
        $userArtists = UserArtist::query()
            ->select('artist_id', 'spotify_artist_id')
            ->where('user_id', auth()->id())
            ->join('artists', 'users_artists.artist_id', '=', 'artists.id')
            ->get();

        // Get artists related to the user's tracked artists but only if they are not already tracked by the user
        $results = ArtistRelatedArtist::query()
            ->whereIn('artist_id', $userArtists->pluck('artist_id'))
            ->whereNotIn('spotify_artist_id', $userArtists->pluck('spotify_artist_id'))
            ->when(strlen($this->search) > 2, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->groupBy('spotify_artist_id') // requires config.database.connections.mysql.strict = false; otherwise without this line it shows duplicates if multiple artists are related to the same artist
            ->orderBy('name')
            ->paginate($this->filter_show);

        return view('livewire.recommended-artists', [
            'results' => $results
        ]);
    }
}
