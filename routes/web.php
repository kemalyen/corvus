<?php

use App\Enums\EventStatus;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Client\EventRegistration;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/debug', function () {
 

        $status = EventStatus::fromName('CANCELLED');

       Event::create([
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'start_time' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'location' => fake()->address,
            'organizer' => fake()->name,
            'capacity' => fake()->numberBetween(10, 100),
            'is_public' => fake()->boolean(80), // 80%
            'status' => $status->value, // Use the name of the enum case
        ]);


})->name('welcome');

Route::redirect('home', '/')->name('home');

Route::get('events/{event}/register', EventRegistration::class)->name('event.registration');


Route::middleware('auth')->group(function () {
    Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('logout', LogoutController::class)
        ->name('logout');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/events/create', \App\Livewire\Events\CreateEvent::class)
        ->name('events.create');

    Route::get('/events/{event}/update', \App\Livewire\events\UpdateEvent::class)
        ->name('events.update');
});
