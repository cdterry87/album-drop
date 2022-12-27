<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RecentReleases extends Component
{
    public function render()
    {
        $results = auth()->user()->albumReleaseMailLogs()
            ->with('album', 'album.artist')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.recent-releases', [
            'results' => $results
        ]);
    }
}
