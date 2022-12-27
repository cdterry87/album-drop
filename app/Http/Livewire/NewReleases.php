<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NewReleases extends Component
{
    use WithPagination;

    public function render()
    {
        $results = auth()->user()->albumReleases()
            ->with('album', 'album.artist')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.new-releases', [
            'results' => $results
        ]);
    }
}
