<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\SearchArtists;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchArtistsTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $this->get(route('search-artists'))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('search-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(SearchArtists::class)
            ->assertSee('Search Artists')
            ->assertSeeHtml('id="no-search"')
            ->assertDontSeeHtml('id="results"')
            ->assertDontSeeHtml('id="no-results"');
    }

    public function test_renders_correctly_without_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('search-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(SearchArtists::class)
            ->assertSee('Search Artists')
            ->assertSeeHtml('id="no-search"')
            ->set('search', 'sdfkjasdlkfjsdlkfjsdfasdf')
            ->call('search')
            ->assertSet('isSearchComplete', true)
            ->assertDontSeeHtml('id="results"')
            ->assertDontSeeHtml('id="no-search"')
            ->assertSeeHtml('id="no-results"');
    }

    public function test_search_renders_correctly_with_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('search-artists'))
            ->assertStatus(200);

        Livewire::actingAs($user)
            ->test(SearchArtists::class)
            ->assertSee('Search Artists')
            ->assertSeeHtml('id="no-search"')
            ->set('search', 'AFI')
            ->call('search')
            ->assertSet('isSearchComplete', true)
            ->assertSeeHtml('id="results"')
            ->assertDontSeeHtml('id="no-search"')
            ->assertDontSeeHtml('id="no-results"');
    }
}
