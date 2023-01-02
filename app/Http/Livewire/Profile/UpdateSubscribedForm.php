<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class UpdateSubscribedForm extends Component
{
    public $subscribed;

    public function mount()
    {
        $this->subscribed = auth()->user()->subscribed;
    }

    public function render()
    {
        return view('livewire.profile.update-subscribed-form');
    }

    public function updateSubscribed()
    {
        auth()->user()->update([
            'subscribed' => $this->subscribed,
        ]);

        $this->emit($this->subscribed ? 'subscribed' : 'unsubscribed');
    }
}
