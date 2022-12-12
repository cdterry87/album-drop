<?php

use Illuminate\Support\Facades\Route;
use Aerni\Spotify\Facades\SpotifyFacade as Spotify;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\RecentReleases;
use App\Http\Livewire\RecommendedArtists;
use App\Http\Livewire\SearchArtists;
use App\Http\Livewire\TrackedArtists;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/dashboard', function () {
    //     $results = Spotify::searchArtists('a day to remember')->get();
    //     dd($results);

    //     return view('dashboard');
    // })->name('dashboard');

    /**
     * Dashboard
     *
     * - Show a few latest albums from saved artists
     * - Show a few recommended artists
     * - Show a few of the user's notifications
     */
    Route::get('dashboard', Dashboard::class)
        ->name('dashboard');

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
     * Recommended Artists (based on saved artists)
     */
    Route::get('recommended-artists', RecommendedArtists::class)
        ->name('recommended-artists');

    /**
     * Recent Releases
     */
    Route::get('recent-releases', RecentReleases::class)
        ->name('recent-releases');

    /**
     * Notifications
     */
    Route::get('notifications', RecentReleases::class)
        ->name('recent-releases');
});
