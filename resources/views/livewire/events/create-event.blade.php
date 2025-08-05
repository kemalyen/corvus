<div>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Create an event') }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ __('Create an event') }}
    </x-slot>


    <div class="pb-5">
        <div class="mx-auto space-y-6">

            <section
                class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg dark:bg-gray-900/50 dark:border dark:border-gray-200/10">
                <div class="w-full max-w-2xl mx-auto">


                    <x-form wire:submit="save" class="mt-6 space-y-6">

                        <x-input label="Title" wire:model="form.title" />
                        <x-textarea label="Description" wire:model="form.description" rows="5" />
                        <x-datetime label="Start Time" wire:model="form.start_time" type="datetime-local" />

                        <x-input label="Location" wire:model="form.location" />
                        <x-input label="Organizer" wire:model="form.organizer" />
                        <x-input label="Capacity" wire:model="form.capacity" />
                        <x-checkbox label="Public" wire:model="form.is_public" hint="Can everyone register this event?" />

                        <x-select label="Status" wire:model="form.status" :options="$status" />


                        <x-slot:actions>
                            <x-button label="Create" class="btn-seconday" type="primary" submit="true" spinner="save" />
                        </x-slot:actions>
                    </x-form>
                </div>
            </section>
        </div>
    </div>
</div>