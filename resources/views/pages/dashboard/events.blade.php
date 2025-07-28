<?php

use App\Models\Event;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use function Laravel\Folio\{middleware, name};

name('events.index');
middleware(['auth', 'verified', 'role:admin,organizer']);
new class extends Component {

    use Toast;
    use WithPagination;

    public string $search = '';

    public array $sortBy = ['column' => 'title', 'direction' => 'desc'];

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-64'],
            ['key' => 'start_time_formatted', 'label' => 'Start Date', 'class' => 'w-8'],
            ['key' => 'organizer', 'label' => 'Organizer', 'class' => 'w-32'],
            ['key' => 'capacity', 'label' => 'Capacity', 'class' => 'w-16'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-24'],
            ['key' => 'public_status', 'label' => 'Public', 'class' => 'w-16'],
        ];
    }

    public function events()
    {
        $user = auth()->user();
        return Event::query()
            ->where(function ($query) use ($user) {
                if ($user->isOrganizer()) {
                    return $query->where('organizer_id', $user->id);
                } else {
                    return $query;
                }
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function () {
                return Event::where('title', 'like', $this->search . '%');
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
        FacadesGate::authorize('delete-event', Event::findOrFail($id));
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

    <x-slot name="title">
        {{ 'List all events' }}
    </x-slot>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Events') }}
        </h2>
    </x-slot>

    @can('create-event')
    <div class="flex justify-end mb-4">
        <x-ui.text-link href="{{ route('events.create') }}" class="btn-ghost btn-sm text-red-600">
            <x-icon name="o-plus" />
            Create Event
        </x-ui.text-link>
    </div>
    @endcan

    @volt('events.index')
    <div class="pb-5">
        <div class="mx-auto space-y-6">
            <x-card shadow>
                <x-table :headers="$headers" :rows="$events" :sort-by="$sortBy" with-pagination>
                    @scope('actions', $event)
                    <div class="flex space-x-2">
                        @can('delete-event', $event)
                        <x-button wire:click="delete({{ $event['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-600" icon="o-trash" />
                        @endcan
                        @can('update-event', $event)
                        <x-button wire:click="edit({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="c-pencil-square" />
                        @endcan
                        @can('view-event', $event)
                        <x-button wire:click="show({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-link" />
                        <x-button wire:click="registrations({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-user" />
                        @endcan

                    </div>
                    @endscope
                </x-table>
            </x-card>
        </div>
    </div>

    @endvolt

</x-layouts.admin>