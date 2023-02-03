<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Aerni\Spotify\Facades\SpotifyFacade;

class UpdateSettingsForm extends Component
{
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

        $session = new Session(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET'),
            env('SPOTIFY_REDIRECT_URI')
        );

        $options = [
            'auto_refresh' => true,
        ];

        $session->refreshAccessToken($user->spotify_refresh_token);
        $accessToken = $session->getAccessToken();
        $session->setAccessToken($accessToken);

        $api = new SpotifyWebAPI($options, $session);
        $api->setSession($session);
        $api->me();

        $newAccessToken = $session->getAccessToken();
        $newRefreshToken = $session->getRefreshToken();

        // Create the playlist
        $playlist = $api->createPlaylist([
            'name' => 'Album Drop',
            'description' => 'My Album Drop Playlist',
            'public' => false,
        ]);

        // Update the user's access and refresh tokens and save the created playlist id
        $user->spotify_playlist_id = $playlist->id;
        $user->spotify_token = $newAccessToken;
        $user->spotify_refresh_token = $newRefreshToken;
        $user->save();
    }
}
