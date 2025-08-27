<?php

use function Laravel\Folio\{middleware, name};

use App\Enums\EventStatus;
use Livewire\Volt\Component;
use App\Models\Event;
use Livewire\WithPagination;


name('home');
new class extends Component
{
    use WithPagination;

    public string $search = '';

    public array $sortBy = ['column' => 'title', 'direction' => 'desc'];

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title', 'class' => 'w-64'],
            ['key' => 'start_time_formatted', 'label' => 'Event Date', 'class' => 'w-8'],
            ['key' => 'organizer', 'label' => 'Organizer', 'class' => 'w-32'],

        ];
    }

    public function events()
    {
        return Event::query()
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->where('status', EventStatus::SCHEDULED)
            ->where('is_public', 1)
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

    public function register(int $id)
    {
        $slug = Event::findOrFail($id)->slug;
        return redirect()->route('event.registration', ['event' => $slug]);
    }
};

?>

<x-layouts.frontend>

    <x-slot name="title">
        {{ 'List all events' }}
    </x-slot>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Scheduled Public Events') }}
        </h2>
    </x-slot>

    @volt('home.index')
    <div class="pb-5">
        <div class="mx-auto space-y-6">
            <x-card shadow>
                <x-table :headers="$headers" :rows="$events" :sort-by="$sortBy" with-pagination>
                    @scope('actions', $event)
                    <div class="flex space-x-2">
                        <x-button wire:click="register({{ $event['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-link" />

                    </div>
                    @endscope
                </x-table>
            </x-card>
        </div>
    </div>
    @endvolt

</x-layouts.frontend>