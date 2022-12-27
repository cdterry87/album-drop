<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NewReleases extends Component
{
    public function render()
    {
        $results = auth()->user()->albumReleases()
            ->with('album', 'album.artist')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.new-releases', [
            'results' => $results
        ]);
    }
}
