<?php


use function Laravel\Folio\{middleware, name};

name('events.show');
middleware(['auth', 'verified', 'role:admin,organizer']);
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
        @can('view-event', $event)
        <x-ui.text-link href="{{ route('events.registrations', ['event' => $event->slug]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Registrations
        </x-ui.text-link>

        <x-ui.text-link href="{{ route('events.registrations.export', ['event' => $event->slug]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Export Registration List
        </x-ui.text-link>
        @endcan
        @can('update-event', $event)
        <x-ui.text-link href="{{ route('events.update', ['event' => $event->slug]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Update Event
        </x-ui.text-link>
        @endcan
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
        <div class="mb-4">
            <strong>{{ __('Registration Link:') }}</strong>
            <p class="text-gray-600 dark:text-gray-400">
                <x-ui.link href="{{ route('event.registration', ['event' => $event->slug]) }}" class="text-blue-600 hover:underline">
                    {{ route('event.registration', ['event' => $event->slug]) }}
                </x-ui.link>
            </p>
        </div>
        @role('admin')
        <div class="mb-4">
            <strong>{{ __('Audits:') }}</strong>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-8">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Event</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actor</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($event->audits as $audit)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $audit->event }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $audit->user->name  }} / {{$audit->user->email}}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $audit->user ? $audit->user->name : 'System' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endrole

    </div>


</x-layouts.admin>