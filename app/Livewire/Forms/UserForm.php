<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user;

    public string $name;
    public string $email;
    public string $password;
    public string $role;

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role; // Assuming role is a string, adjust if it's an enum or something else
    }

    public function store(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->user) {
            $this->user->update($data);
        } else {
            User::create($data);
        }
    }   
}
