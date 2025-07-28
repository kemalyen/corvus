<?php

namespace App\Providers;

use App\Policies\EventPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::define('create-user', [UserPolicy::class, 'create']);
        Gate::define('update-user', [UserPolicy::class, 'update']);

        Gate::define('create-event', [EventPolicy::class, 'create']);
        Gate::define('update-event', [EventPolicy::class, 'update']);

        Gate::define('delete-event', [EventPolicy::class, 'delete']);
        Gate::define('view-event', [EventPolicy::class, 'view']);
        Gate::define('view-any-event', [EventPolicy::class, 'viewAny']);

    }
}
