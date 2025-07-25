<?php
 
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Client\EventRegistration;
use Illuminate\Support\Facades\Route;
 

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

    Route::get('/users/create', \App\Livewire\Users\CreateUser::class)
        ->name('users.create');

    Route::get('/users/{user}/update', \App\Livewire\Users\UpdateUser::class)
        ->name('users.update');


    Route::get('/events/create', \App\Livewire\Events\CreateEvent::class)
        ->name('events.create');

    Route::get('/events/{event}/update', \App\Livewire\Events\UpdateEvent::class)
        ->name('events.update');
});
