<?php

use App\Models\User;
use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;

name('users.index');
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
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-64'],
            ['key' => 'role', 'label' => 'Role', 'class' => 'w-16'],
        ];
    }

    public function users()
    {
        return User::query()
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function () {
                return User::where('name', 'like', $this->search . '%');
            })->paginate(10);
    }


    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers()
        ];
    }

    public function delete(int $id)
    {
        $product = User::findOrFail($id);
        $product->delete();
        $this->toast('User deleted successfully.', 'success');
    }

    public function edit(int $id)
    {
        return redirect()->route('users.update', ['user' => $id]);
    }

    public function show(int $id)
    {
        return redirect()->route('users.show', ['user' => $id]);
    }

   
};
?>
<x-layouts.admin>

    <x-slot name="title">
        {{ 'List all users' }}
    </x-slot>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="flex justify-end mb-4">
        <x-ui.text-link href="{{ route('users.create') }}" class="btn-ghost btn-sm text-red-600">
            <x-icon name="o-plus" />
            Create user
        </x-ui.text-link>
    </div>

    @volt('events.index')
    <div class="pb-5">
        <div class="mx-auto space-y-6">
            <x-card shadow>
                <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination>
                    @scope('actions', $user)
                    <div class="flex space-x-2">
                        <x-button wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-600" icon="o-trash" />
                        <x-button wire:click="edit({{ $user['id'] }})" class="btn-ghost btn-sm text-red-600" icon="c-pencil-square" />
                    </div>
                    @endscope
                </x-table>
            </x-card>
        </div>
    </div>
    </div>
    @endvolt

</x-layouts.admin>