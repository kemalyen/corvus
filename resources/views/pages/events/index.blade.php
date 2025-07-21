<?php

use App\Models\Event;
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;

name('events.index');
middleware(['auth', 'verified']);

new class extends Component {

    use Toast;
    use WithPagination;

    public string $search = '';

    public array $sortBy = ['column' => 'name', 'direction' => 'desc'];

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => 'Tile', 'class' => 'w-64'],
            ['key' => 'start_time_formatted', 'label' => 'Start Date', 'class' => 'w-8'],
            ['key' => 'organizer', 'label' => 'Organizer', 'class' => 'w-32'],
            ['key' => 'capacity', 'label' => 'Capacity', 'class' => 'w-16'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-24'],
            ['key' => 'public_status', 'label' => 'Public', 'class' => 'w-16'],
        ];
    }

    public function events()
    {
        return Event::query()
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function () {
                return Event::where('name', 'like', $this->search . '%');
            })->paginate(10);
    }


    public function with(): array
    {
        return [
            'events' => $this->events(),
            'headers' => $this->headers()
        ];
    }

    public function delete(int $id)
    {
        $product = Event::findOrFail($id);
        $product->delete();
        $this->toast('Product deleted successfully.', 'success');
    }

    public function edit(int $id)
    {
        return redirect()->route('events.update', ['event' => $id]);
    }

    public function show(int $id)
    {
        return redirect()->route('events.show', ['event' => $id]);
    }

    public function registrations(int $id)
    {
        return redirect()->route('events.registrations', ['event' => $id]);
    }
};
?>



<x-layouts.admin>

    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="flex justify-end mb-4">
        <x-ui.text-link href="{{ route('events.create') }}" class="btn-ghost btn-sm text-red-600" >
            <x-icon name="o-plus" />
            Create Event
        </x-ui.text-link>
    </div>

    @volt('events.index')
    <div class="pb-5">
        <div class="mx-auto space-y-6">
            <x-card shadow>
                <x-table :headers="$headers" :rows="$events" :sort-by="$sortBy" with-pagination>
                    @scope('actions', $event)
                    <div class="flex space-x-2">
                        <x-button wire:click="delete({{ $event['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-600" icon="o-trash" />
                        <x-button wire:click="edit({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="c-pencil-square" />
                        <x-button wire:click="show({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-link" />
                        <x-button wire:click="registrations({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-user" />
                    </div>
                    @endscope
                </x-table>
            </x-card>
        </div>
    </div>
    </div>
    @endvolt

</x-layouts.admin>