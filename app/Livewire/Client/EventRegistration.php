<?php

namespace App\Livewire\Client;

use App\Livewire\Forms\EventRegistrationForm;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component; 

/**
 * EventRegistration Component
 *
 * This component handles the event registration process.
 * It uses the EventRegistrationForm for validation and submission.
 */
#[Layout('components.layouts.frontend')]
class EventRegistration extends Component
{

    public $event;
    public EventRegistrationForm $form;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->form->setEvent($event);
    }

    public function save()
    {
        $this->form->store();
    }

    public function render()
    {
        return view('livewire.client.event-registration', [
            'event' => $this->event,
        ])->title('Event Registration - ' . $this->event->title);
    }
}
