<?php

use App\Models\Event;
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;

name('events.registrations');
middleware(['auth', 'verified']);
new class extends Component {

    use Toast;
    use WithPagination;

    public  Event $event;

    public string $search = '';

    public array $sortBy = ['column' => 'name', 'direction' => 'desc'];

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-8'],
            ['key' => 'phone', 'label' => 'Phone', 'class' => 'w-32'],
            ['key' => 'registered_at', 'label' => 'Registered At', 'class' => 'w-24'],
        ];
    }

    public function registrations()
    {
        return $this->event->registrations()
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])->paginate(10);
    }

    public function mount(Event $event)
    {
        $this->event = $event;
    }


    public function with(): array
    {
        return [
            'registrations' => $this->registrations(),
            'headers' => $this->headers()
        ];
    }

    public function show(int $id)
    {
        return redirect()->route('events.registrations.show', ['EventRegistration' => $id]);
    }
};
?>



<x-layouts.admin>

    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Registrations for Event: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="flex justify-end mb-4">
        <x-ui.text-link href="{{ route('events.show', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600">
            Visit back Event
        </x-ui.text-link>

        <x-ui.text-link href="{{ route('events.registrations.export', ['event' => $event->id]) }}" class="btn-ghost btn-sm text-red-600 p-2">
            Export Registration List
        </x-ui.text-link>

    </div>

    @volt('events.registrations')
    <div class="pb-5">
        <div class="mx-auto space-y-6">
            <x-card shadow>
                <x-table :headers="$headers" :rows="$registrations" :sort-by="$sortBy" with-pagination>
                    @scope('actions', $event)
                    <div class="flex space-x-2">
                        <x-button wire:click="show({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-link" />
                    </div>
                    @endscope
                </x-table>
            </x-card>
        </div>
    </div>

    @endvolt

</x-layouts.admin>