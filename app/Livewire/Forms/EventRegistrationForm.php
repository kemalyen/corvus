<?php

namespace App\Livewire\Forms;

use App\Mail\NewEventRegistration;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EventRegistrationForm extends Form
{
    public Event $event;

    public string $name = '';

    public string $email  = '';

    public string $phone = '';

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
            'phone' => $this->phone]));
 
        $this->reset(['name', 'email', 'phone']);

        
        session()->flash('register-status', 'Thank you for registering for the event!');
    }
}
