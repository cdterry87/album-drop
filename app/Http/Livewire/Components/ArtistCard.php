<?php

namespace App\Http\Livewire\Components;

use App\Models\Artist;
use Livewire\Component;

class ArtistCard extends Component
{
    public $name, $image, $spotifyId;
    public $isTracked = false;

    public function render()
    {
        $this->determineIsTracked();

        return view('livewire.components.artist-card');
    }

    /**
     * Save artist to the user's tracked artists.
     */
    public function trackArtist()
    {
        if ($this->isTracked) {
            // If artist is already being tracked, delete it
            Artist::query()
                ->where('user_id', auth()->id())
                ->where('artist_id', $this->spotifyId)
                ->delete();
        } else {
            // If artist is not being tracked, create it
            Artist::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'image' => $this->image,
                'artist_id' => $this->spotifyId,
            ]);
        }
    }

    /**
     * Determine if the artist is already tracked by this user.
     */
    public function determineIsTracked()
    {
        $this->isTracked = Artist::query()
            ->where('user_id', auth()->id())
            ->where('artist_id', $this->spotifyId)
            ->exists();
    }
}
