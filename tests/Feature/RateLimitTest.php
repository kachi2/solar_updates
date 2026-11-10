<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_product_pages_are_limited_per_minute(): void
    {
        config(['sofar.rate_limits.product_pages_per_minute' => 3]);
        $product = $this->createProduct();

        for ($i = 0; $i < 3; $i++) {
            $this->get(route('product-details', $product))->assertOk();
        }

        $this->get(route('product-details', $product))->assertStatus(429);
        $this->get('/')->assertOk(); // other pages still work
    }

    public function test_site_wide_page_limit(): void
    {
        config(['sofar.rate_limits.pages_per_minute' => 5]);

        for ($i = 0; $i < 5; $i++) {
            $this->get('/pages/news')->assertOk();
        }

        $this->get('/pages/news')->assertStatus(429)->assertHeader('Retry-After');
    }

    public function test_guests_sharing_an_ip_but_using_different_browsers_have_separate_limits(): void
    {
        config(['sofar.rate_limits.product_pages_per_minute' => 3]);
        $product = $this->createProduct();
        $sharedIp = ['REMOTE_ADDR' => '102.89.1.1'];

        for ($i = 0; $i < 3; $i++) {
            $this->withServerVariables($sharedIp)->withHeader('User-Agent', 'iPhone Safari')->get(route('product-details', $product))->assertOk();
        }
        $this->withServerVariables($sharedIp)->withHeader('User-Agent', 'iPhone Safari')->get(route('product-details', $product))->assertStatus(429);

        // Another phone on the same mobile network is not affected.
        $this->withServerVariables($sharedIp)->withHeader('User-Agent', 'Samsung Internet')->get(route('product-details', $product))->assertOk();
    }

    public function test_logged_in_customers_have_their_own_limit(): void
    {
        config(['sofar.rate_limits.product_pages_per_minute' => 3]);
        $product = $this->createProduct();
        $sharedIp = ['REMOTE_ADDR' => '102.89.1.2'];

        // A guest on the shared IP uses up the allowance…
        for ($i = 0; $i < 4; $i++) {
            $this->withServerVariables($sharedIp)->get(route('product-details', $product));
        }

        // …but a logged-in customer on the same IP and browser still gets in.
        $this->actingAs($this->createCustomer())->withServerVariables($sharedIp)
            ->get(route('product-details', $product))->assertOk();
    }

    public function test_normal_shopping_is_not_blocked_by_default_limits(): void
    {
        $product = $this->createProduct();

        // A busy shopper: 40 product views and 40 other pages in a minute.
        for ($i = 0; $i < 40; $i++) {
            $this->get(route('product-details', $product))->assertOk();
            $this->get('/pages/products')->assertOk();
        }
    }
}
