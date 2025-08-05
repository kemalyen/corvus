<?php

namespace App\Livewire\Client;

use App\Enums\RegistrationStatus;
use App\Exceptions\RateLimiterException;
use App\Livewire\Forms\EventRegistrationForm;
use App\Models\Event;
use Illuminate\Support\Facades\RateLimiter;
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
        $registrations_count = $this->event->registrations()->where('status', RegistrationStatus::CONFIRMED->value)->count();
        
        return view('livewire.client.event-registration', [
            'event' => $this->event,
            'registrations_count' => $registrations_count,
        ])->title('Event Registration - ' . $this->event->title);
    }
}
