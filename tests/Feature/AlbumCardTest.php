<?php

namespace Tests\Feature;

use App\Http\Livewire\Components\AlbumCard;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumCardTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_correctly()
    {
        Livewire::test(AlbumCard::class, [
            'name' => 'Album Name',
            'image' => 'image.jpeg',
            'artist' => 'Artist Name',
            'url' => 'https://example.com',
            'releaseDate' => new \DateTime('2023-01-01'),
        ])
            ->assertSee('Album Name')
            ->assertSee('image.jpeg')
            ->assertSee('Artist Name')
            ->assertSee('https://example.com')
            ->assertSee('January 1, 2023');
    }
}
