<?php

use App\Enums\EventStatus;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Client\EventRegistration;
use App\Models\Event;
use App\Models\EventRegistration as ModelsEventRegistration;
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
 

return ModelsEventRegistration::join('events', 'events.id', '=', 'event_registrations.event_id')
                ->select('event_registrations.*', 'events.title as event_title')
                ->orderByDesc('created_at')->toSql();

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

    Route::get('/events/{event}/update', \App\Livewire\Events\UpdateEvent::class)
        ->name('events.update');
});
