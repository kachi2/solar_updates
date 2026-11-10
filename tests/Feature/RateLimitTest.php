<?php

namespace Tests\Feature;

use App\Models\BlockedVisitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_login_allows_three_attempts_per_five_minutes(): void
    {
        $this->createCustomer(['email' => 'ada@example.com']);

        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', ['email' => 'ada@example.com', 'password' => 'wrong'])->assertStatus(302);
        }

        $this->post('/login', ['email' => 'ada@example.com', 'password' => 'wrong'])
            ->assertStatus(429)
            ->assertSee('Please slow down a little');

        // After the 5-minute window the customer can try again.
        $this->travel(301)->seconds();
        $this->post('/login', ['email' => 'ada@example.com', 'password' => 'wrong'])->assertStatus(302);
    }

    public function test_admin_login_is_limited_too(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('admin.login.submit'), ['email' => 'boss@example.com', 'password' => 'x']);
        }

        $this->post(route('admin.login.submit'), ['email' => 'boss@example.com', 'password' => 'x'])->assertStatus(429);
    }

    public function test_signup_allows_three_per_five_minutes(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/register', ['name' => 'Bot', 'email' => "bot{$i}@example.com", 'phone' => "080000000{$i}", 'password' => 'password', 'password_confirmation' => 'password']);
            auth()->logout();
        }

        $this->post('/register', ['name' => 'Bot', 'email' => 'bot9@example.com', 'phone' => '0800000009', 'password' => 'password', 'password_confirmation' => 'password'])
            ->assertStatus(429);

        $this->assertDatabaseMissing('users', ['email' => 'bot9@example.com']);
    }

    public function test_pages_are_limited_to_twenty_per_ten_minutes(): void
    {
        $product = $this->createProduct();

        // Product pages count as pages too.
        for ($i = 0; $i < 10; $i++) {
            $this->get('/pages/news')->assertOk();
            $this->get(route('product-details', $product))->assertOk();
        }

        $this->get('/pages/news')->assertStatus(429)->assertHeader('Retry-After');

        // After the 10-minute window the visitor can browse again.
        $this->travel(11)->minutes();
        $this->get('/pages/news')->assertOk();
    }

    public function test_blocked_visitors_are_recorded_with_their_ip_range(): void
    {
        config(['sofar.rate_limits.pages.requests' => 2]);
        $bot = ['REMOTE_ADDR' => '203.0.113.57'];

        for ($i = 0; $i < 6; $i++) {
            $this->withServerVariables($bot)->withHeader('User-Agent', 'ScraperBot/1.0')->get('/pages/news');
        }

        $this->assertDatabaseHas('blocked_visitors', [
            'ip' => '203.0.113.57',
            'ip_range' => '203.0.113.0/24',
            'user_agent' => 'ScraperBot/1.0',
            'times_blocked' => 1, // written once per 5 minutes, not per request
        ]);

        $this->travel(6)->minutes();
        $this->withServerVariables($bot)->withHeader('User-Agent', 'ScraperBot/1.0')->get('/pages/news')->assertStatus(429);
        $this->assertDatabaseHas('blocked_visitors', ['ip' => '203.0.113.57', 'times_blocked' => 2]);
    }

    public function test_ipv6_ranges_are_grouped_by_64(): void
    {
        $this->assertSame('2001:db8:85a3:8d3::/64', BlockedVisitor::rangeOf('2001:db8:85a3:8d3:1319:8a2e:370:7348'));
    }

    public function test_cart_ajax_requests_are_not_counted(): void
    {
        config(['sofar.rate_limits.pages.requests' => 2]);
        $product = $this->createProduct();

        for ($i = 0; $i < 5; $i++) {
            $this->getJson(route('carts.add', ['id' => $product->id, 'qty' => 1]), ['X-Requested-With' => 'XMLHttpRequest'])->assertOk();
        }

        $this->get('/pages/news')->assertOk();
    }

    public function test_admin_area_is_not_limited(): void
    {
        config(['sofar.rate_limits.pages.requests' => 2]);
        $admin = $this->createAdmin();

        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($admin, 'admin')->get(route('product.index'))->assertOk();
        }
    }

    public function test_guests_sharing_an_ip_but_using_different_browsers_have_separate_limits(): void
    {
        config(['sofar.rate_limits.pages.requests' => 3]);
        $sharedIp = ['REMOTE_ADDR' => '102.89.1.1'];

        for ($i = 0; $i < 3; $i++) {
            $this->withServerVariables($sharedIp)->withHeader('User-Agent', 'iPhone Safari')->get('/pages/news')->assertOk();
        }
        $this->withServerVariables($sharedIp)->withHeader('User-Agent', 'iPhone Safari')->get('/pages/news')->assertStatus(429);

        // Another phone on the same mobile network is not affected.
        $this->withServerVariables($sharedIp)->withHeader('User-Agent', 'Samsung Internet')->get('/pages/news')->assertOk();
    }

    public function test_logged_in_customers_have_their_own_limit(): void
    {
        config(['sofar.rate_limits.pages.requests' => 3]);
        $sharedIp = ['REMOTE_ADDR' => '102.89.1.2'];

        // A guest on the shared IP uses up the allowance…
        for ($i = 0; $i < 4; $i++) {
            $this->withServerVariables($sharedIp)->get('/pages/news');
        }

        // …but a logged-in customer on the same IP and browser still gets in.
        $this->actingAs($this->createCustomer())->withServerVariables($sharedIp)
            ->get('/pages/news')->assertOk();
    }

    public function test_expired_cache_rows_are_pruned(): void
    {
        config(['cache.default' => 'database']);
        DB::table('cache')->insert([
            ['key' => 'old', 'value' => 'i:1;', 'expiration' => now()->subHour()->getTimestamp()],
            ['key' => 'live', 'value' => 'i:1;', 'expiration' => now()->addHour()->getTimestamp()],
        ]);

        $this->artisan('cache:prune-expired')->assertSuccessful();

        $this->assertDatabaseMissing('cache', ['key' => 'old']);
        $this->assertDatabaseHas('cache', ['key' => 'live']);
    }
}
