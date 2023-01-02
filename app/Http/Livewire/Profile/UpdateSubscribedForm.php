<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class UpdateSubscribedForm extends Component
{
    public $subscribed = false;

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
        User::where('id', auth()->user()->id)->update([
            'subscribed' => $this->subscribed,
        ]);

        $this->emit($this->subscribed ? 'subscribed' : 'unsubscribed');
    }
}
