<?php


use function Laravel\Folio\{middleware, name};

name('events.show');
middleware(['auth', 'verified']);
?>

<x-layouts.admin>

  <x-slot name="title">
       {{ __('Event Detail: ') . $event->title }}
    </x-slot>

    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Event Detail: ') . $event->title }}
        </h2>
    </x-slot>

    <div class="flex justify-end mb-4">
        <x-ui.text-link href="{{ route('events.registrations', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Registrations
        </x-ui.text-link>
        
        <x-ui.text-link href="{{ route('events.update', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Update Event
        </x-ui.text-link>

        <x-ui.text-link href="{{ route('events.registrations.export', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Export Registration List
        </x-ui.text-link>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
        <div class="mb-4">
            <strong>{{ __('Title:') }}</strong> {{ $event->title }}
        </div>
        <div class="mb-4">
            <strong>{{ __('Date:') }}</strong> {{ $event->start_time->format('F j, Y') }}
        </div>
        <div class="mb-4">
            <strong>{{ __('Location:') }}</strong> {{ $event->location }}
        </div>
        <div class="mb-4">
            <strong>{{ __('Organizer:') }}</strong> {{ $event->organizer }}
        </div>
        <div class="mb-4">
            <strong>{{ __('Status:') }}</strong> {{ $event->status->value }}
        </div>
        <div class="mb-4">
            <strong>{{ __('Public:') }}</strong> {{ $event->public_status }}
        </div>
        <div class="mb-4">
            <strong>{{ __('Description:') }}</strong>
            <p>{{ $event->description }}</p>
        </div>
    </div>


</x-layouts.admin>