<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Livewire\Profile\UpdateSettingsForm;

class UpdateSettingsFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_must_be_authenticated()
    {
        $this->get(route('profile.show'))
            ->assertRedirect(route('login'));
    }

    public function test_renders_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertStatus(200)
            ->assertSee('Account Settings');
    }

    public function test_can_update_subscribed()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UpdateSettingsForm::class)
            ->set('subscribed', true)
            ->call('updateSettings')
            ->assertEmitted('settingsUpdated');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'subscribed' => true,
        ]);

        Livewire::actingAs($user)
            ->test(UpdateSettingsForm::class)
            ->set('subscribed', false)
            ->call('updateSettings')
            ->assertEmitted('settingsUpdated');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'subscribed' => false,
        ]);
    }
}
