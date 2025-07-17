<div>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Create a product') }}
        </h2>
    </x-slot>

    <div class="pb-5">
        <div class="mx-auto space-y-6">

            <section
                class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg dark:bg-gray-900/50 dark:border dark:border-gray-200/10">
                <div class="max-w-xl">


                    <x-form wire:submit="save" class="mt-6 space-y-6">

                        <x-input label="Title" wire:model="form.title" />
                        <x-textarea label="Description" wire:model="form.description" rows="5" />
                        <x-input label="Stock" wire:model="form.stock" />
                        <x-input label="Price" wire:model="form.price" />
                        <x-input label="SKU" wire:model="form.sku" />
                        <x-checkbox label="Active" wire:model="form.is_active" />
                        <x-choices label="Category" wire:model="form.category_id" :options="$categories" placeholder="Search ..."
                            single
                            searchable />

                            <x-file wire:model="form.image_path" label="Image"/>


                        <x-slot:actions>
                            <x-button label="Cancel" />
                            <x-button label="Create" class="btn-seconday" type="primary" submit="true" spinner="save" />
                        </x-slot:actions>
                    </x-form>
                </div>
            </section>
        </div>
    </div>
</div>