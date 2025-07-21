<?php

namespace App\Livewire\Events;

use App\Enums\EventStatus;
use App\Livewire\Forms\EventForm;
use App\Models\Event;
use Livewire\Component;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class UpdateEvent extends Component
{
    public $status;

    public EventForm $form;

    public function mount(Event $event)
    {
        $this->populateStatus();
        $this->form->setEvent($event);
    }
    
    public function save()
    {
        $this->form->save();

        return $this->redirect('/events');
    }

    public function populateStatus()
    {
        $this->status = EventStatus::toCollection();
    }
    public function render()
    {
        return view('livewire.events.update-event');
    }
}
