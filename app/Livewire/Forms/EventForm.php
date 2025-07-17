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
    public string $title = '';

    public $description;

    #[Validate('required|date')]
    public string $start_date = '';

    public string $location = '';

    public string $organizer = '';

    public int $capacity = 0;

    public bool $is_public = true;

    public EventStatus $status = EventStatus::SCHEDULED;
}
