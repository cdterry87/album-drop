<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UserArtist;
use App\Models\ArtistAlbum;
use App\Models\ArtistRelatedArtist;

class Home extends Component
{
    public $isUserSubscribed = false;

    public function mount()
    {
        // Check if user is subscribed
        $this->isUserSubscribed = auth()->user()->subscribed;
    }

    public function render()
    {
        // Get the last 5 new releases for the user
        $newReleases = ArtistAlbum::query()
            ->where('release_date', '>=', now()->subDays(30))
            ->whereIn('artist_id', function ($query) {
                $query->select('artist_id')
                    ->from('users_artists')
                    ->where('user_id', auth()->id())
                    ->pluck('artist_id');
            })
            ->orderBy('release_date', 'desc')
            ->get();

        // Get new releases count
        $newReleasesCount = $newReleases->count();

        // Get 5 latest new releases
        $latestNewReleases = $newReleases->take(4);

        // Get all user's artists ordered by most recent
        $allUserArtists = UserArtist::query()
            ->where('user_id', auth()->id())
            ->join('artists', 'users_artists.artist_id', '=', 'artists.id')
            ->orderBy('users_artists.created_at', 'desc')
            ->get();

        // Get number of artists user is tracking
        $trackedArtistsCount = $allUserArtists->count();

        // Get last 5 artists user is tracking
        $latestTrackedArtists = $allUserArtists->take(4);

        // Get artists recommended to the user based on their tracked artists
        $recommendedArtists = ArtistRelatedArtist::query()
            ->whereIn('artist_id', $allUserArtists->pluck('artist_id'))
            ->whereNotIn('spotify_artist_id', $allUserArtists->pluck('spotify_artist_id'))
            ->groupBy('spotify_artist_id') // requires config.database.connections.mysql.strict = false; otherwise without this line it shows duplicates if multiple artists are related to the same artist
            ->inRandomOrder()
            ->get();

        // Get recommended artists count
        $recommendedArtistsCount = $recommendedArtists->count();

        // Get random recommended artists
        $randomRecommendedArtists = $recommendedArtists->take(8);

        return view('livewire.home', [
            'trackedArtistsCount' => $trackedArtistsCount,
            'newReleasesCount' => $newReleasesCount,
            'recommendedArtistsCount' => $recommendedArtistsCount,
            'latestTrackedArtists' => $latestTrackedArtists,
            'latestNewReleases' => $latestNewReleases,
            'randomRecommendedArtists' => $randomRecommendedArtists
        ]);
    }

    public function subscribe()
    {
        User::where('id', auth()->user()->id)->update([
            'subscribed' => true,
        ]);

        $this->isUserSubscribed = true;
    }
}
