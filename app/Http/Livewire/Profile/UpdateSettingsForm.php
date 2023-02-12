<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use Aerni\Spotify\Facades\SpotifyFacade;
use App\Traits\SpotifyTrait;

class UpdateSettingsForm extends Component
{
    use SpotifyTrait;

    public $subscribed = false;
    public $create_mega_playlist = false;
    public $create_new_releases_playlist = false;
    public $spotify_id = null;

    public function mount()
    {
        $user = auth()->user();

        $this->subscribed = $user->subscribed;
        $this->create_mega_playlist = $user->create_mega_playlist;
        $this->create_new_releases_playlist = $user->create_new_releases_playlist;
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
            'create_mega_playlist' => $this->create_mega_playlist,
            'create_new_releases_playlist' => $this->create_new_releases_playlist,
        ]);

        if ($this->create_mega_playlist) {
            // Check if user already has a Spotify playlist attached to their account and if it exists on Spotify
            $playlist = null;
            if ($user && $user->spotify_mega_playlist_id) {
                $playlist = SpotifyFacade::playlist($user->spotify_mega_playlist_id)->get();
            }

            // If a playlist doesn't exist, create one
            if (!$playlist) {
                $this->createPlaylist();
            }
        }

        if ($this->create_new_releases_playlist) {
            // Check if user already has a Spotify playlist attached to their account and if it exists on Spotify
            $albumDropsPlaylist = null;
            if ($user && $user->spotify_new_releases_playlist_id) {
                $albumDropsPlaylist = SpotifyFacade::playlist($user->spotify_new_releases_playlist_id)->get();
            }

            // If a playlist doesn't exist, create one
            if (!$albumDropsPlaylist) {
                $this->createAlbumDropsPlaylist();
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
            'name' => 'Mega Playlist by Album Drop',
            'description' => 'My Album Drop Mega Playlist',
            'public' => false,
        ]);

        // Add the created playlist to the user's account
        $user->spotify_mega_playlist_id = $playlist->id;
        $user->save();
    }

    public function createAlbumDropsPlaylist()
    {
        $user = auth()->user();

        $api = $this->createSpotifySession($user);

        // Create the playlist
        $playlist = $api->createPlaylist([
            'name' => 'New Releases by Album Drop',
            'description' => 'My Album Drop New Releases Playlist',
            'public' => false,
        ]);

        // Add the created playlist to the user's account
        $user->spotify_new_releases_playlist_id = $playlist->id;
        $user->save();
    }
}
