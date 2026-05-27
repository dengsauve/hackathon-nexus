<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\ProjectEntry;
use App\Models\Team;
use App\Policies\EventPolicy;
use App\Policies\ProjectEntryPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(ProjectEntry::class, ProjectEntryPolicy::class);
    }
}
