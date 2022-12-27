<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\UserArtist;
use Illuminate\Support\Facades\DB;
use App\Models\ArtistRelatedArtist;
use Illuminate\Support\Facades\Config;

class RecommendedArtists extends Component
{
    protected $listeners = ['refreshTrackedArtists' => '$refresh'];

    public function render()
    {
        // Get user's artists
        $userArtists = UserArtist::select('artist_id', 'spotify_artist_id')
            ->join('artists', 'users_artists.artist_id', '=', 'artists.id')
            ->get();

        // Get artists related to the user's tracked artists but only if they are not already tracked by the user
        $results = ArtistRelatedArtist::query()
            ->whereIn('artist_id', $userArtists->pluck('artist_id'))
            ->whereNotIn('spotify_artist_id', $userArtists->pluck('spotify_artist_id'))
            ->groupBy('spotify_artist_id') // requires config.database.connections.mysql.strict = false; otherwise without this line it shows duplicates if multiple artists are related to the same artist
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.recommended-artists', [
            'results' => $results
        ]);
    }
}
