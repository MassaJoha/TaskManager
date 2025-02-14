<?php

namespace App\Providers;

use App\Models\Subtask;
use App\Observers\SubtaskObserver;
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
        Subtask::observe(SubtaskObserver::class);
    }
}
