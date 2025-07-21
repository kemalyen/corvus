<?php

use App\Exports\RegistrationsExport;
use App\Models\Event;
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

name('events.registrations.export');
middleware(['auth', 'verified']);
new class extends Component {

    use Toast;
    use WithPagination;

    public  Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->registration = $event->registrations()
            ->orderBy('created_at', 'desc')->get();
    }

    public function download(int $id, string $type)
    {
        if ($type === 'csv') {
            return (new RegistrationsExport($id))->download('registrations-' . $id . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        // Default to Excel format      
        return (new RegistrationsExport($id))->download('registrations-' . $id . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
};
?>

<x-layouts.admin>

  <x-slot name="title">
       Export Registrations for Event: {{ $event->title }}
    </x-slot>

    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Export Registrations for Event: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="flex justify-end mb-4">
        <x-ui.text-link href="{{ route('events.show', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Visit back Event
        </x-ui.text-link>

        <x-ui.text-link href="{{ route('events.registrations', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Registration List
        </x-ui.text-link>

    </div>

    @volt('events.registrations')

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-4">Export Registrations</h3>
        <p class="mb-4">You can download the registration list for the event in CSV format.</p>
        <p class="mb-4">Click the button below to download the registration list.</p>

        <div class="flex items-center py-2 border-b">
            <a href="#" wire:click.prevent="download({{ $event->id }}, 'excel')" class="btn btn-info btn-sm m-2">
                Download Excel
            </a>

            <a href="#" wire:click.prevent="download({{ $event->id }}, 'csv')" class="btn btn-info btn-sm m-2">
                Download CSV
            </a>
        </div>
    </div>

    @endvolt

</x-layouts.admin>