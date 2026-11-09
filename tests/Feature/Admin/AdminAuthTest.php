<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_guests_are_sent_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin-login'));
        $this->get('/admin/orders')->assertRedirect(route('admin-login'));
    }

    public function test_admin_login_page_renders(): void
    {
        $this->get(route('admin-login'))->assertOk();
    }

    public function test_admin_can_log_in_and_login_details_are_recorded(): void
    {
        $admin = $this->createAdmin();

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.index'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('web');
        $this->assertNotNull($admin->fresh()->last_login);
        $this->assertNotNull($admin->fresh()->login_ip);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->createAdmin();

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'wrong',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_customer_login_does_not_grant_admin_access(): void
    {
        $this->actingAs($this->createCustomer())
            ->get('/admin')
            ->assertRedirect(route('admin-login'));
    }

    public function test_admin_can_log_out(): void
    {
        $this->actingAs($this->createAdmin(), 'admin')
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin-login'));

        $this->assertGuest('admin');
    }
}
