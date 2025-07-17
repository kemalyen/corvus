<?php

namespace App\Livewire\Events;

use App\Livewire\Forms\EventForm;
use App\Models\Event;
use Livewire\Component;

class CreateEvent extends Component
{
    public EventForm $form;

    public function render()
    {
        return view('livewire.events.create-event');
    }
}
