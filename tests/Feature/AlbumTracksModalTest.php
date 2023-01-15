<?php

namespace Tests\Feature;

use App\Http\Livewire\Components\AlbumTracksModal;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumTracksModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_correctly_without_album_tracks()
    {
        Livewire::test(AlbumTracksModal::class)
            ->assertSee('no-album-tracks')
            ->assertSee('closeModal');
    }

    public function test_renders_correctly_with_album_tracks()
    {
        $spotifyId = '1eIzVBHA5NvX0wo2nLACew';
        $album = 'Sing the Sorrow';
        $releaseDate = new \DateTime('2003-01-01');

        Livewire::test(AlbumTracksModal::class)
            ->call('viewTracks', $spotifyId, $album, $releaseDate)
            ->assertSet('isModalShown', true)
            ->assertSee('album-tracks')
            ->assertSee($album)
            ->assertSee($releaseDate->format('F j, Y'))
            ->call('closeModal')
            ->assertSet('isModalShown', false)
            ->assertSet('results', null);
    }
}
