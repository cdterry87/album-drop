<?php

namespace App\Http\Livewire\Components;

use App\Models\Artist;
use Livewire\Component;
use App\Models\UserArtist;

class ArtistCard extends Component
{
    public $name, $image, $url, $spotifyId;
    public $hasAlbums = false;
    public $isTracked = false;

    public function render()
    {
        $this->determineIsTracked();

        return view('livewire.components.artist-card');
    }

    /**
     * Save artist and add it to user's tracked artists.
     */
    public function trackArtist()
    {
        // Save artist
        $artist = Artist::updateOrCreate(['spotify_artist_id' => $this->spotifyId], [
            'name' => $this->name,
            'image' => $this->image,
            'url' => $this->url,
        ]);

        // Add artist to user's tracked artists
        UserArtist::updateOrCreate([
            'user_id' => auth()->id(),
            'artist_id' => $artist->id,
        ]);

        $this->emit('refreshTrackedArtists');
    }

    /**
     * Remove artist from user's tracked artists.
     */
    public function untrackArtist()
    {
        // Find the artist's id
        $artist = Artist::query()
            ->where('spotify_artist_id', $this->spotifyId)
            ->first();

        // Remove artist from user's tracked artists
        UserArtist::query()
            ->where('user_id', auth()->id())
            ->where('artist_id', $artist->id)
            ->delete();

        $this->emit('refreshTrackedArtists');
    }

    /**
     * Determine if the artist is already tracked by this user.
     */
    public function determineIsTracked()
    {
        $this->isTracked = Artist::query()
            ->where('spotify_artist_id', $this->spotifyId)
            ->whereHas('userArtists', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->exists();
    }
}
