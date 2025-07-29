<?php

namespace App\Livewire\Events;

use App\Enums\EventStatus;
use App\Exceptions\RateLimiterException;
use App\Livewire\Forms\EventForm;
use App\Models\Event;
use  Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class CreateEvent extends Component
{
    public $status;

    public EventForm $form;

    public function mount()
    {
        Gate::authorize('create-event');
        $this->populateStatus();
    }

    public function save()
    {

        $this->form->store();

        return $this->redirect('/dashboard/events');
    }

    public function populateStatus()
    {
        $this->status = EventStatus::toCollection();
    }

    public function render()
    {
        $user = auth()->user();

        if (RateLimiter::tooManyAttempts('create-event:' . $user->id, $perMinute = 5)) {
            throw new RateLimiterException('You are creating events too quickly. Please wait a moment before trying again.');
        }

        RateLimiter::increment('create-event:'.$user->id);
 
        return view('livewire.events.create-event');
    }
}
