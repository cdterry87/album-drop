<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\NewReleases;
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
    /**
     * Dashboard
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
     * Artist Albums
     *
     * @todo - on the Tracked Artists page, each artist should have a link to their albums.
     */

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
