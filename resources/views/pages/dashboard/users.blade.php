<?php

use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\WithPagination;
use function Laravel\Folio\{middleware, name};
use App\Traits\ClearsFilters;
use Illuminate\Pagination\LengthAwarePaginator;

name('users.index');
middleware(['auth', 'verified', 'role:admin']);
new class extends Component {

    use Toast, ClearsFilters;
    use WithPagination;

   
    public bool $drawer = false;

    public string $search = '';

    public array $sortBy = ['column' => 'name', 'direction' => 'desc'];

    public int $perPage = 10;

    // Filter count
    public function filters()
    {
        $count = 0;

        if (!empty($this->search)) {
            $count++;
        }
 

        return $count;
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-32'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-64'],
            ['key' => 'email_verified_at', 'label' => 'Email Verified', 'class' => 'w-12', 'format' => ['date', 'd/M/Y']],
            ['key' => 'role', 'label' => 'Role', 'class' => 'w-16'],
            ['key' => 'created_at', 'label' => 'Created At', 'class' => 'w-16', 'format' => ['date', 'd/M/Y']],
        ];
    }

    public function users(): LengthAwarePaginator
    {
        return User::query()->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role')
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function () {
                return User::where('name', 'like', $this->search . '%');
            })->paginate($this->perPage);
    }


    public function with(): array
    {
        return [
            'users' => $this->users(),
            'headers' => $this->headers(),
            'filters' => $this->filters(),

        ];
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
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

    @volt('users.index')
    <div class="pb-5">
        <div class="mx-auto space-y-6">

            <x-header title="Users" separator progress-indicator>
                <x-slot:middle class="!justify-end">
                    <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
                </x-slot:middle>
                <x-slot:actions>
                    <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" :badge="$filters" />
                </x-slot:actions>
            </x-header>

            <x-card shadow>
                <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy"
                    with-pagination
                    per-page="perPage"
                    :per-page-values="[3, 5, 10]">
                    @scope('actions', $user)
                    <div class="flex space-x-2">
                        <x-button wire:click="delete({{ $user['id'] }})" wire:confirm="Are you sure?" spinner class="btn-ghost btn-sm text-red-600" icon="o-trash" />
                        <x-button wire:click="edit({{ $user['id'] }})" class="btn-ghost btn-sm text-red-600" icon="c-pencil-square" />
                    </div>
                    @endscope
                </x-table>
            </x-card>


            <!-- FILTER DRAWER -->
            <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
                <div class="grid gap-5">
                    <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass"
                        @keydown.enter="$wire.drawer = false" />

                </div>

                <x-slot:actions>
                    <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
                    <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
                </x-slot:actions>
            </x-drawer>

        </div>
    </div>

    @endvolt


</x-layouts.admin>