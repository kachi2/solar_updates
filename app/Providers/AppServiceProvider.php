<?php

namespace App\Providers;

use App\View\Composers\SharedViewDataComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // One composer instance per request so shared view data is queried once.
        $this->app->scoped(SharedViewDataComposer::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.*', 'includes.*', 'users.*', 'manage.*', 'auth.*', 'errors.*'], SharedViewDataComposer::class);
    }
}
