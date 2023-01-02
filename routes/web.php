<?php

use App\Models\User;
use App\Http\Livewire\Home;
use App\Http\Livewire\NewReleases;
use App\Http\Livewire\ArtistAlbums;
use App\Http\Livewire\SearchArtists;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\TrackedArtists;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Livewire\RecommendedArtists;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Welcome
 */
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/**
 * Route for logging in with Spotify
 */
Route::get('/auth/spotify/redirect', function () {
    return Socialite::driver('spotify')
        ->scopes(['user-read-email']) // Make sure we can read the user's email address
        ->redirect();
})->name('login.spotify');

/**
 * Route for handling the callback from logging in with Spotify
 */
Route::get('/auth/spotify/callback', function () {
    $spotifyUser = Socialite::driver('spotify')->user();

    $user = User::updateOrCreate([
        'spotify_id' => $spotifyUser->id,
        'email' => $spotifyUser->email,
    ], [
        'name' => $spotifyUser->name,
        'spotify_token' => $spotifyUser->token,
        'spotify_refresh_token' => $spotifyUser->refreshToken,
    ]);

    Auth::login($user, true);

    return redirect()->route('home');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    /**
     * Home
     */
    Route::get('home', Home::class)
        ->name('home');

    /**
     * Search Artists
     */
    Route::get('search-artists', SearchArtists::class)
        ->name('search-artists');

    /**
     * Tracked Artists
     */
    Route::get('tracked-artists', TrackedArtists::class)
        ->name('tracked-artists');

    /**
     * Artist Albums
     */
    Route::get('artist-albums/{artistSpotifyId}', ArtistAlbums::class)
        ->name('artist-albums');

    /**
     * Recommended Artists (based on tracked artists)
     */
    Route::get('recommended-artists', RecommendedArtists::class)
        ->name('recommended-artists');

    /**
     * New Releases
     */
    Route::get('new-releases', NewReleases::class)
        ->name('new-releases');
});
