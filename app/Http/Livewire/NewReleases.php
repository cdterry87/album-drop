<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ArtistAlbum;
use Livewire\WithPagination;

class NewReleases extends Component
{
    use WithPagination;

    public $filter_days = 30;

    public function render()
    {
        // Get all albums released in the specified timeframe from artists the user is tracking
        $results = ArtistAlbum::query()
            ->when($this->filter_days > 0, function ($query) {
                return $query->where('release_date', '>=', now()->subDays($this->filter_days));
            })
            ->whereIn('artist_id', function ($query) {
                $query->select('artist_id')
                    ->from('users_artists')
                    ->where('user_id', auth()->id())
                    ->pluck('artist_id');
            })
            ->orderBy('release_date', 'desc')
            ->paginate(12);

        return view('livewire.new-releases', [
            'results' => $results
        ]);
    }
}
