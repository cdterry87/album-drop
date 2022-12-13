<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;

class TrackedArtists extends Component
{
    protected $listeners = ['refreshTrackedArtists' => '$refresh'];

    public $search;

    public function render()
    {
        $results = Artist::where('user_id', auth()->id())
            ->when(strlen($this->search) > 2, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->get();

        return view('livewire.tracked-artists', [
            'results' => $results,
        ]);
    }
}
