<?php

namespace App\Http\Livewire\Components;

use App\Models\Artist;
use Livewire\Component;

class ArtistCard extends Component
{
    public $name, $image, $genres, $spotifyId;
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
        // If artist is not being tracked, create it
        Artist::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'image' => $this->image,
            'artist_id' => $this->spotifyId,
        ]);

        $this->emit('refreshTrackedArtists');
    }

    /**
     * Remove artist from user's tracked artists.
     */
    public function untrackArtist()
    {
        Artist::query()
            ->where('user_id', auth()->id())
            ->where('artist_id', $this->spotifyId)
            ->delete();

        $this->emit('refreshTrackedArtists');
    }

    /**
     * @todo - when an artist is added or removed, we also need
     *  to add/remove their genres from the user's genres list
     * this will help us with the recommended artists feature
     */

    /**
     * Determine if the artist is already tracked by this user.
     */
    protected function determineIsTracked()
    {
        $this->isTracked = Artist::query()
            ->where('user_id', auth()->id())
            ->where('artist_id', $this->spotifyId)
            ->exists();
    }
}
