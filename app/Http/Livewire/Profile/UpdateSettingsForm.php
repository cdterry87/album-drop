<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Aerni\Spotify\Facades\SpotifyFacade;
use App\Traits\SpotifyTrait;

class UpdateSettingsForm extends Component
{
    use SpotifyTrait;

    public $subscribed = false;
    public $create_playlist = false;
    public $spotify_id = null;

    public function mount()
    {
        $user = auth()->user();

        $this->subscribed = $user->subscribed;
        $this->create_playlist = $user->create_playlist;
        $this->spotify_id = $user->spotify_id;
    }

    public function render()
    {
        return view('livewire.profile.update-settings-form');
    }

    public function updateSettings()
    {
        $user = auth()->user();

        User::where('id', $user->id)->update([
            'subscribed' => $this->subscribed,
            'create_playlist' => $this->create_playlist,
        ]);

        if ($this->create_playlist) {
            // Check if user already has a Spotify playlist attached to their account and if it exists on Spotify
            $playlist = null;
            if ($user && $user->spotify_playlist_id) {
                $playlist = SpotifyFacade::playlist($user->spotify_playlist_id)->get();
            }

            // If a playlist doesn't exist, create one
            if (!$playlist) {
                $this->createPlaylist();
            }
        }

        $this->emit('settingsUpdated');
    }

    public function createPlaylist()
    {
        $user = auth()->user();

        $api = $this->createSpotifySession($user);

        // Create the playlist
        $playlist = $api->createPlaylist([
            'name' => 'Album Drop',
            'description' => 'My Album Drop Playlist',
            'public' => false,
        ]);

        // Add the created playlist to the user's account
        $user->spotify_playlist_id = $playlist->id;
        $user->save();
    }
}
