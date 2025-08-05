<?php

namespace App\Livewire\Forms;

use App\Exceptions\RateLimiterException;
use App\Mail\NewEventRegistration;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EventRegistrationForm extends Form
{
    public Event $event;

    public string $name = '';

    public string $email  = '';

    public string $phone = '';

    public ?string $registration_code = null;

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:event_registrations,email,NULL,id,event_id,' . ($this->event?->id ?? 'NULL'),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'registration_code' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!$this->event->is_public && $value !== $this->event->registration_code) {
                        $fail('The registration code is invalid.');
                    }
                },
            ],  
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email has already been registered for this event.',
            'phone.required' => 'Please enter your phone number.',
        ];
    }


    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function store(): void
    {
        if (RateLimiter::tooManyAttempts('register-event:' . request()->ip(), $perMinute = 3)) {
            throw new RateLimiterException('You are registering events too quickly. Please wait a moment before trying again!.');
        }
        RateLimiter::increment('register-event:' . request()->ip());

        $this->validate();

        $this->event->registrations()->create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_attending' => true, // Assuming default is attending
            'registered_at' => Carbon::now(),
        ]);

        Mail::to($this->email)->queue(new NewEventRegistration($this->event, [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone
        ]));

        $this->reset(['name', 'email', 'phone', 'registration_code']);


        session()->flash('register-status', 'Thank you for registering for the event!');
    }
}
