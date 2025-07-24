<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CreateUser extends Component
{
    public function render()
    {
        return view('livewire.users.create-user');
    }
}
