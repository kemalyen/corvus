<div>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Update a user') }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ __('Update a user') }}
    </x-slot>


    <div class="pb-5">
        <div class="mx-auto space-y-6">

            <section
                class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg dark:bg-gray-900/50 dark:border dark:border-gray-200/10">
                <div class="w-full max-w-2xl mx-auto">


                    <x-form wire:submit="save" class="mt-6 space-y-6">

                        <x-input label="Name" wire:model="form.name" />
                        <x-input label="Email" wire:model="form.email" />
                        <x-input label="Password" wire:model="form.password" type="password" />
                        <x-input label="Password Confirmation" wire:model="form.password_confirmation" type="password" />
                        <x-select label="Role" wire:model="form.role" :options="$roles" />
                     
                        <x-slot:actions>
                            <x-button label="Cancel" />
                            <x-button label="Update" class="btn-seconday" type="primary" submit="true" spinner="save" />
                        </x-slot:actions>
                    </x-form>
                </div>
            </section>
        </div>
    </div>
</div>