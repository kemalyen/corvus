<?php

namespace App\Livewire\Client;

use App\Exceptions\RateLimiterException;
use App\Livewire\Forms\EventRegistrationForm;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
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
    public ?string $captchaToken = null;
    public EventRegistrationForm $form;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->form->setEvent($event);
    }

    public function save()
    {

   $query = http_build_query([
        'secret' => config('services.recaptcha.secret_key'),
        'response' => $this->captchaToken,
    ]);
 
    $response = Http::post('https://www.google.com/recaptcha/api/siteverify?' . $query);
    $captchaLevel = $response->json('score');

    throw_if($captchaLevel <= 0.5, ValidationException::withMessages([
        'captchaToken' => __('Error on captcha verification. Please, refresh the page and try again.')
    ]));

        $this->form->store();
    }

    public function render()
    {
        return view('livewire.client.event-registration', [
            'event' => $this->event,
        ])->title('Event Registration - ' . $this->event->title);
    }
}
