<?php

namespace App\Traits;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

trait SpotifyTrait
{
    public function createSpotifySession($user)
    {
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

        $user->spotify_token = $newAccessToken;
        $user->spotify_refresh_token = $newRefreshToken;
        $user->save();

        return $api;
    }
}
