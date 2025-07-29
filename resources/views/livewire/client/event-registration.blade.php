
<div class="shadow-lg rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-200/10">

    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-4 lg:space-y-0  min-h-[400px]">

        <div class="lg:w-full mx-auto px-8 space-y-6">
            <h3 class="text-2xl">{{ $event->title }}</h3>
            <p>{{ $event->description }}</p>
            <p>Location: {{ $event->location }}</p>
            <p>Organizer: {{ $event->organizer }}</p>
            <p>Start Time: {{ $event->start_time->format('d M Y H:i') }}</p>
        </div>

        <div class="mx-auto px-8 lg:ml-8 lg:mt-0 mt-8 w-full lg:w-3/5">
            <div class="mx-auto space-y-6 ">
                <section
                    class="bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg dark:bg-gray-900/50 dark:border dark:border-gray-200/10">
                    @if (session('register-status'))
                    <div class="alert alert-warning mb-4">
                        {{ session('register-status') }}
                    </div>
                    @endif
                    @if( $event->status !== \App\Enums\EventStatus::SCHEDULED )
                    <div class="alert alert-warning mb-4">
                        This event is not open for registration.
                    </div>
                    @else
                    <h3 class="text-lg font-semibold mb-4">Register for Event</h3>
                    <p class="mb-4">Please fill in your details to register for the event.</p>
                    
                    <x-form wire:submit="save" class="mt-1 space-y-2">
                        <x-input label="Name" wire:model="form.name" />
                        <x-input label="Email" wire:model="form.email" />
                        <x-input label="Phone" wire:model="form.phone" />
                        <x-slot:actions>
                            <x-button label="Cancel" />
                            <x-button label="Register" class="btn-seconday" type="primary" submit="true" spinner="save" />
                        </x-slot:actions>
                    </x-form>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>