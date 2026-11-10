<?php

namespace App\Providers;

use App\View\Composers\SharedViewDataComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        View::composer([
            'layouts.*', 'includes.*', 'users.*', 'manage.*', 'auth.*',
            'errors.404', 'errors.419', 'errors.429', 'errors::404', 'errors::419', 'errors::429',
        ], SharedViewDataComposer::class);

        $this->configureRateLimits();
    }

    /**
     * Request limits (values in config/sofar.php → rate_limits). Static files
     * (images, PDFs, CSS) are served by the web server and are not counted.
     */
    private function configureRateLimits(): void
    {
        // Every page, per visitor: generous for shoppers, slows scrapers down.
        RateLimiter::for('web', fn (Request $request) => Limit::perMinute(config('sofar.rate_limits.pages_per_minute'))
            ->by('web|'.$this->visitorKey($request)));

        // Product pages are what scrapers crawl most.
        RateLimiter::for('product', fn (Request $request) => Limit::perMinute(config('sofar.rate_limits.product_pages_per_minute'))
            ->by('product|'.$this->visitorKey($request)));

        // Login attempts per email + IP (customer and admin logins).
        RateLimiter::for('login', fn (Request $request) => Limit::perMinutes(
            config('sofar.rate_limits.login.minutes'),
            config('sofar.rate_limits.login.attempts'),
        )->by(Str::lower((string) $request->input('email')).'|'.$request->ip()));

        // Account sign-ups per IP.
        RateLimiter::for('register', fn (Request $request) => Limit::perMinutes(
            config('sofar.rate_limits.register.minutes'),
            config('sofar.rate_limits.register.attempts'),
        )->by($request->ip()));
    }

    /**
     * Who a page/product limit counts against. Logged-in customers get their own
     * allowance; guests are counted per IP + browser, because many customers can
     * share one public IP (mobile networks, offices) but not one browser string.
     * Scrapers usually send a single browser string, so they are still caught.
     */
    private function visitorKey(Request $request): string
    {
        if ($user = $request->user()) {
            return 'user:'.$user->getAuthIdentifier();
        }

        return 'guest:'.$request->ip().'|'.sha1((string) $request->userAgent());
    }
}
