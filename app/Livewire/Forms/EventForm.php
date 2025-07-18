<?php

namespace App\Livewire\Forms;

use App\Enums\EventStatus;
use App\Models\Event;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EventForm extends Form
{
    public ?Event $event;

    #[Validate('required|string|max:255')]
    public string $title;

    public $description;

    #[Validate('required|date|after:today')]
    public $start_time;

    #[Validate('required|string|max:255')]
    public string $location;

    #[Validate('required|string|max:255')]
    public string $organizer;

    #[Validate('required|integer')]
    public int $capacity;

    #[Validate('boolean')]
    public bool $is_public = false;

    #[Validate('required')]
    public $status;

    public function setEvent(Event $event): void
    {
        $this->event = $event;

        $this->title = $event->title;
        $this->description = $event->description;
        $this->start_time =  $event->start_time->format('Y-m-d H:i'); //'2025-10-12 13:10'; //$event->start_time->format('dd/mm/Y h:i'); // Ensure the format is compatible with datetime-local input
        $this->location = $event->location;
        $this->organizer = $event->organizer;
        $this->capacity = $event->capacity;
        $this->is_public = $event->is_public;
        $this->status = $event->status->name; // Default status, can be changed based on your logic
    }

    public function store(): void
    {
        $this->validate();

        $status = EventStatus::tryFrom($this->status) ?? EventStatus::DRAFT;

        Event::create([
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'location' => $this->location,
            'organizer' => $this->organizer,
            'capacity' => $this->capacity,
            'is_public' => ($this->is_public) ? true : false,
            'status' => $status,
        ]);
    }

    public function save(): void
    {
        if ($this->event) {
            $status = EventStatus::fromName($this->status) ?? EventStatus::DRAFT;
 
            $this->event->update([
                'title' => $this->title,
                'description' => $this->description,
                'start_time' => $this->start_time,
                'location' => $this->location,
                'organizer' => $this->organizer,
                'capacity' => $this->capacity,
                'is_public' => ($this->is_public) ? true : false,
                'status' => $status->value
            ]);
        }
    }
}
