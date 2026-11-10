<?php

namespace App\Providers;

use App\Models\BlockedVisitor;
use App\View\Composers\SharedViewDataComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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
        // The shop theme is Bootstrap 4; Laravel's default pagination markup is for Tailwind.
        Paginator::useBootstrapFour();

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
        // Page views per visitor (config sofar.rate_limits.pages). Not counted: the admin
        // area, background (AJAX) requests such as add-to-cart, and the Flutterwave
        // payment return, so a paying customer never lands on "Too many requests".
        RateLimiter::for('web', function (Request $request) {
            if ($request->is('admin', 'admin/*') || $request->ajax() || $request->routeIs('verify.pay', 'confirm.payment')) {
                return Limit::none();
            }

            return Limit::perMinutes(
                config('sofar.rate_limits.pages.minutes'),
                config('sofar.rate_limits.pages.requests'),
            )->by('web|'.$this->visitorKey($request))
                // Over the limit: remember the IP (blocked_visitors) and show the "Too many requests" page.
                ->response(function (Request $request, array $headers) {
                    BlockedVisitor::record($request);

                    return response()->view('errors.429', [], 429, $headers);
                });
        });

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
