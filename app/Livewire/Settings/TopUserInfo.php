<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;

class TopUserInfo extends Component
{
    protected $listeners = [
        'updateTopUserInfo' => '$refresh'
    ];
    public function render()
    {
        return view('livewire.settings.top-user-info',[
            'user' => User::query()->findOrFail(auth()->id())
        ]);
    }
}
