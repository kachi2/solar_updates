<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Transaction;
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

    public function test_orders_list_shows_each_order_once(): void
    {
        $user = $this->createCustomer();
        $order = $this->createUnpaidOrder($user, 251); // 1 line
        OrderItem::create([
            'user_id' => $user->id, 'order_No' => $order->order_No,
            'product_name' => 'Lithium Battery', 'qty' => 1, 'price' => 100, 'payable' => 100,
        ]);
        $this->createUnpaidOrder($this->createCustomer()); // someone else's

        $html = $this->actingAs($user)->get(route('users.orders'))
            ->assertOk()
            ->assertSee('1 order')
            ->assertSee('Order #'.$order->order_No)
            ->assertSee('+1 more')
            ->assertSee('Awaiting payment')
            ->getContent();

        $this->assertSame(1, substr_count($html, 'Order #'.$order->order_No));
        $this->assertSame(1, substr_count($html, 'class="sofar-orders__row"'));
    }

    public function test_account_overview(): void
    {
        $user = $this->createCustomer(['name' => 'ada obi', 'created_at' => '2026-03-15 10:00:00']);
        $order = $this->createUnpaidOrder($user); // also creates a default address

        $this->actingAs($user)->get(route('users.account'))
            ->assertOk()
            ->assertSee('Hi, Ada')
            ->assertSee('Member since Mar 2026')
            ->assertDontSee('2626')
            ->assertSee('ada obi')
            ->assertSee('1 Test Street')
            ->assertSee('/update-address/', false)
            ->assertSee('Order #'.$order->order_No)
            ->assertSee('View all (1)');
    }

    public function test_account_overview_without_address_or_orders(): void
    {
        $this->actingAs($this->createCustomer())->get(route('users.account'))
            ->assertOk()
            ->assertSee("You haven't saved a delivery address yet.", false)
            ->assertSee("You haven't placed any orders yet.", false);
    }

    public function test_payments_page_lists_own_payments(): void
    {
        $user = $this->createCustomer();
        Transaction::create([
            'user_id' => $user->id, 'order_No' => '123456789', 'type' => 'payment for', 'payment_method' => 'card',
            'payment_ref' => 'SFSL445566', 'external_ref' => 'FLW-REF-1', 'amount' => 2500000,
        ]);
        Transaction::create([
            'user_id' => $this->createCustomer()->id, 'order_No' => '999', 'payment_ref' => 'SOMEONE-ELSE', 'amount' => 1,
        ]);

        $this->actingAs($user)->get(route('user.transactions'))
            ->assertOk()
            ->assertSee('order #123456789')
            ->assertSee('SFSL445566')
            ->assertSee('FLW-REF-1')
            ->assertSee('₦2,500,000')
            ->assertDontSee('SOMEONE-ELSE')
            ->assertDontSee('Prev Balance');
    }

    public function test_payments_page_empty_state(): void
    {
        $this->actingAs($this->createCustomer())->get(route('user.transactions'))
            ->assertOk()
            ->assertSee('No payments yet');
    }

    public function test_address_book_lists_addresses_with_default_first(): void
    {
        $user = $this->createCustomer();
        $this->createDefaultAddress($user, ['receiver_name' => 'Home Ada', 'is_default' => 1]);
        $this->createDefaultAddress($user, ['receiver_name' => 'Office Ada', 'is_default' => 0]);

        $html = $this->actingAs($user)->get(route('users.address'))
            ->assertOk()
            ->assertSee('Home Ada')
            ->assertSee('Office Ada')
            ->assertSee('Set as default')
            ->getContent();

        $this->assertLessThan(strpos($html, 'Office Ada'), strpos($html, 'Home Ada'));
        $this->assertSame(1, substr_count($html, 'Set as default'));
    }

    public function test_set_default_address(): void
    {
        $user = $this->createCustomer();
        $home = $this->createDefaultAddress($user, ['is_default' => 1]);
        $office = $this->createDefaultAddress($user, ['is_default' => 0]);
        $intruder = $this->createCustomer();

        $this->actingAs($intruder)->post(route('address.default', encrypt($office->id)))->assertNotFound();

        $this->actingAs($user)->post(route('address.default', encrypt($office->id)))->assertSessionHas('success');

        $this->assertEquals(0, $home->fresh()->is_default);
        $this->assertEquals(1, $office->fresh()->is_default);
    }

    public function test_removing_the_default_address_promotes_another(): void
    {
        $user = $this->createCustomer();
        $home = $this->createDefaultAddress($user, ['is_default' => 1]);
        $office = $this->createDefaultAddress($user, ['is_default' => 0]);

        $this->actingAs($user)->get(route('address.delete', encrypt($home->id)))->assertSessionHas('success');

        $this->assertModelMissing($home);
        $this->assertEquals(1, $office->fresh()->is_default);
    }

    public function test_add_and_edit_address_pages_render(): void
    {
        $user = $this->createCustomer();
        $address = $this->createDefaultAddress($user, ['receiver_name' => 'Ada Obi', 'city' => 'Ikeja']);

        $this->actingAs($user)->get(route('users.add-address'))
            ->assertOk()->assertSee('Add address')->assertSee('Use as my default delivery address');

        $this->actingAs($user)->get(route('users.update-address', encrypt($address->id)))
            ->assertOk()
            ->assertSee('Edit address')
            ->assertSee('value="Ada Obi"', false)
            ->assertSee('value="Ikeja"', false)
            ->assertSee('This is your default delivery address.')
            ->assertDontSee('Remove this address'); // only address on the account

        $this->createDefaultAddress($user, ['is_default' => 0]);
        $this->actingAs($user)->get(route('users.update-address', encrypt($address->id)))
            ->assertSee('Remove this address');
    }

    public function test_the_only_address_cannot_be_removed(): void
    {
        $user = $this->createCustomer();
        $address = $this->createDefaultAddress($user);

        $this->actingAs($user)->get(route('users.address'))->assertOk()->assertDontSee('>Remove<', false);

        $this->actingAs($user)->get(route('address.delete', encrypt($address->id)))
            ->assertRedirect(route('users.address'))
            ->assertSessionHas('error', 'You need at least one delivery address. Add another address before removing this one.');

        $this->assertModelExists($address);
    }

    public function test_new_address_marked_default_takes_over(): void
    {
        $user = $this->createCustomer();
        $old = $this->createDefaultAddress($user);

        $this->actingAs($user)->post(route('address.store', $user->id), [
            'name' => 'Office', 'address' => '5 Broad St', 'phone' => '8030000000', 'default' => '1',
        ])->assertRedirect(route('users.address'))->assertSessionHas('success', 'Address added.');

        $this->assertEquals(0, $old->fresh()->is_default);
        $this->assertDatabaseHas('shippings', ['user_id' => $user->id, 'receiver_name' => 'Office', 'is_default' => 1]);
    }

    public function test_editing_the_default_address_keeps_it_default(): void
    {
        $user = $this->createCustomer();
        $address = $this->createDefaultAddress($user);

        $this->actingAs($user)->post(route('address.store', $address->id), [
            'update' => '1', 'name' => 'Renamed', 'address' => '1 Test Street', 'phone' => '8031234567',
        ])->assertRedirect(route('users.address'));

        $address->refresh();
        $this->assertSame('Renamed', $address->receiver_name);
        $this->assertEquals(1, $address->is_default);
    }

    public function test_address_form_shows_field_errors(): void
    {
        $user = $this->createCustomer();

        $this->actingAs($user)->from(route('users.add-address'))
            ->post(route('address.store', $user->id), ['name' => '', 'address' => '', 'phone' => ''])
            ->assertRedirect(route('users.add-address'))
            ->assertSessionHasErrors(['name', 'address', 'phone']);

        $this->actingAs($user)->get(route('users.add-address'))->assertSee('is-invalid', false);
    }

    public function test_order_statuses_show_correctly_to_customers(): void
    {
        $user = $this->createCustomer();

        // is_delivered = 1 means "Initiated" in the admin, not "Delivered".
        $initiated = $this->createUnpaidOrder($user);
        $initiated->update(['is_delivered' => 1, 'dispatch_status' => 0]);

        $delivered = $this->createUnpaidOrder($user);
        $delivered->update(['is_paid' => 1, 'is_delivered' => 1, 'dispatch_status' => 2]);

        $cancelled = $this->createUnpaidOrder($user);
        $cancelled->update(['is_delivered' => 3]);

        $this->actingAs($user)->get(route('users.order-details', encrypt($initiated->order_No)))
            ->assertOk()->assertSee('Processing')->assertDontSee('>Delivered<', false);

        $this->actingAs($user)->get(route('users.order-details', encrypt($delivered->order_No)))
            ->assertOk()->assertSee('Paid')->assertSee('Delivered');

        $this->actingAs($user)->get(route('users.order-details', encrypt($cancelled->order_No)))
            ->assertOk()->assertSee('Cancelled')->assertSee('sofar-status--danger', false);
    }

    public function test_orders_list_empty_state(): void
    {
        $this->actingAs($this->createCustomer())->get(route('users.orders'))
            ->assertOk()
            ->assertSee('No orders yet');
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
