<?php

namespace Database\Seeders;

use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        config('database.connections.mysql') ?? DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Model::unguard();

        EventRegistration::truncate();
        Event::truncate();
        User::truncate();

        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ConnectRelationshipsSeeder::class);
        //$this->call('UsersTableSeeder');

        Model::reguard();
        config('database.connections.mysql') ?? DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $organizerRole = config('roles.models.role')::where('name', '=', 'Organizer')->first();
        $organizers = User::factory(3)->create();

        $organizers->each(function ($user) use ($organizerRole) {
            $user->attachRole($organizerRole);
        });

        $events = Event::factory(10)->recycle($organizers)->create();

        EventRegistration::factory(500)->recycle($events)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $role = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $user->attachRole($role);
    }
}
