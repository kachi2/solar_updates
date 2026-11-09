<?php

namespace Tests\Feature;

use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_first_address_becomes_the_default(): void
    {
        $user = $this->createCustomer();

        $this->actingAs($user)
            ->post(route('address.store', $user->id), [
                'name' => 'Ada', 'address' => '2 Allen Ave', 'phone' => '8030000000', 'city' => 'Ikeja', 'state' => 'Lagos',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('shippings', ['user_id' => $user->id, 'receiver_name' => 'Ada', 'is_default' => 1]);
    }

    public function test_address_is_saved_for_the_logged_in_user_not_the_url_id(): void
    {
        $user = $this->createCustomer();
        $other = $this->createCustomer();

        $this->actingAs($user)->post(route('address.store', $other->id), [
            'name' => 'Ada', 'address' => '2 Allen Ave', 'phone' => '8030000000',
        ]);

        $this->assertDatabaseHas('shippings', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('shippings', ['user_id' => $other->id]);
    }

    public function test_customers_cannot_touch_other_customers_addresses(): void
    {
        $owner = $this->createCustomer();
        $address = $this->createDefaultAddress($owner);
        $intruder = $this->createCustomer();

        $this->actingAs($intruder)->get(route('address.delete', encrypt($address->id)))->assertNotFound();
        $this->actingAs($intruder)->get(route('users.update-address', encrypt($address->id)))->assertNotFound();
        $this->actingAs($intruder)->post(route('address.store', $address->id), [
            'update' => 'update Address', 'name' => 'Hacked', 'address' => 'x', 'phone' => '1',
        ])->assertNotFound();

        $this->assertDatabaseHas('shippings', ['id' => $address->id, 'receiver_name' => $owner->name]);
    }

    public function test_customers_cannot_view_other_customers_orders(): void
    {
        $order = $this->createUnpaidOrder($this->createCustomer());

        $this->actingAs($this->createCustomer())
            ->get(route('users.order-details', encrypt($order->order_No)))
            ->assertNotFound();
    }

    public function test_customer_can_view_own_order(): void
    {
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user);

        $this->actingAs($user)->get(route('users.order-details', encrypt($order->order_No)))->assertOk();
    }

    public function test_password_change_requires_the_old_password(): void
    {
        $user = $this->createCustomer(['password' => Hash::make('old-password')]);

        $this->actingAs($user)->post(route('update.user-details'), [
            'name' => $user->name, 'email' => $user->email,
            'oldpassword' => 'wrong', 'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertSessionHas('error', 'Old Password is Incorrect');

        $this->actingAs($user)->post(route('update.user-details'), [
            'name' => 'New Name', 'email' => $user->email,
            'oldpassword' => 'old-password', 'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_registration_creates_welcome_notification(): void
    {
        $this->post('/register', [
            'name' => 'New Customer', 'email' => 'new@example.com', 'phone' => '08099999999',
            'password' => 'password', 'password_confirmation' => 'password',
        ])->assertRedirect(route('index'));

        $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'phone' => '08099999999']);
        $this->assertTrue(Notification::where('title', 'welcome to sofarsolar')->exists());
        $this->assertDatabaseHas('admin_notifies', ['message' => 'New Customer Registered']);
    }

    public function test_notifications_page_and_delete_only_own(): void
    {
        $user = $this->createCustomer();
        $mine = Notification::create(['user_id' => $user->id, 'title' => 'Hi', 'message' => 'Hello']);
        $theirs = Notification::create(['user_id' => $this->createCustomer()->id, 'title' => 'Other', 'message' => 'x']);

        $this->actingAs($user)->get(route('user.notification'))->assertOk()->assertSee('Hi');
        $this->actingAs($user)->get(route('notify.delete', encrypt($theirs->id)));
        $this->actingAs($user)->get(route('notify.delete', encrypt($mine->id)));

        $this->assertModelMissing($mine);
        $this->assertModelExists($theirs);
    }
}
