<?php

use App\Models\Event;
use App\Models\EventRegistration;

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;

name('dashboard');
middleware(['auth', 'verified', 'role:admin, organizer']);
new class extends Component
{
    use Toast;

    public int $perPage = 10;
    use WithPagination;

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'event_title', 'label' => 'Event', 'class' => 'w-64'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-8'],
            ['key' => 'phone', 'label' => 'Phone', 'class' => 'w-32'],
            ['key' => 'registered_at', 'label' => 'Registered At', 'class' => 'w-24'],
        ];
    }

    public function registrations()
    {
        $user = auth()->user();
        return EventRegistration::join('events', 'events.id', '=', 'event_registrations.event_id')
            ->select('event_registrations.*', 'events.title as event_title')
            ->where(function ($query) use ($user) {
                if ($user->isOrganizer()) {
                    return $query->where('events.organizer_id', $user->id);
                } else {
                    return $query;
                }
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);
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

    <x-slot name="title">
        {{ __('Dashboard') }}
    </x-slot>

    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @volt('dashboard')
    <div class="flex flex-col flex-1">
        <div class="flex flex-col  flex-1 pb-5 mx-auto  w-full">
            <div class="relative flex-1 w-full ">
                <div class="flex justify-between items-center w-full bg-pink- overflow-hidden border border-dashed bg-gradient-to-br from-white to-zinc-50 rounded-lg border-zinc-200 dark:border-gray-700 dark:from-gray-950 dark:via-gray-900 dark:to-gray-800">
                    <div class="flex relative flex-col p-10 h-full w-full">
                        <div class="flex items-center pb-5 mb-5 space-x-1.5 text-lg font-bold text-gray-800 uppercase border-b border-dotted border-zinc-200 dark:border-gray-800 dark:text-gray-200">
                            Welcome to Admin Dashboard
                        </div>

                        <div class="pb-5">
                            <div class="mx-auto space-y-6">
                                <x-card shadow>

                                    @if($registrations && $registrations->count())
                                    <x-table :headers="$headers" :rows="$registrations"
                                        with-pagination
                                        per-page="perPage"
                                        :per-page-values="[3, 5, 10]">
                                        @scope('actions', $registration)
                                        <div class="flex space-x-2">
                                            <x-button wire:click="show({{ $registration['id'] }})" class="btn-ghost btn-sm text-red-600" icon="o-link" />
                                        </div>
                                        @endscope
                                    </x-table>
                                    
                                    @else
                                    <div class="text-center text-gray-500 dark:text-gray-400">
                                        No registrations found.
                                    </div>
                                    @endif
                                </x-card>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.admin>