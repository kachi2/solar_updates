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
        // errors.500 is left out on purpose: it must render even when the database is down.
        // Laravel renders HTTP error pages as "errors::404" (namespaced), so both names are listed.
        View::composer(['layouts.*', 'includes.*', 'users.*', 'manage.*', 'auth.*', 'errors.404', 'errors.419', 'errors::404', 'errors::419'], SharedViewDataComposer::class);
    }
}
