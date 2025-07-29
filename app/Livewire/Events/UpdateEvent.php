<?php

namespace App\Livewire\Events;

use App\Enums\EventStatus;
use App\Exceptions\RateLimiterException;
use App\Livewire\Forms\EventForm;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class UpdateEvent extends Component
{
    public $status;

    public EventForm $form;

    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
        Gate::authorize('update-event', $event);
        $this->populateStatus();
        $this->form->setEvent($event);
    }

    public function save()
    {
         $user = auth()->user();
        if (RateLimiter::tooManyAttempts('update-event:' . $user->id, $perMinute = 5)) {
            throw new RateLimiterException('You are updating events too quickly. Please wait a moment before trying again.');
        }

        RateLimiter::increment('update-event:'.$user->id);

        $this->form->save();

        return $this->redirect('/dashboard/events');
    }

    public function populateStatus()
    {
        $this->status = EventStatus::toCollection();
    }

    public function render()
    {
        $user = auth()->user();

        if (RateLimiter::tooManyAttempts('update-event:' . $user->id, $perMinute = 5)) {
            throw new RateLimiterException('You are updating events too quickly. Please wait a moment before trying again.');
        }

        RateLimiter::increment('update-event:'.$user->id);

        return view('livewire.events.update-event', [
            'event' => $this->event
        ]);
    }
}
