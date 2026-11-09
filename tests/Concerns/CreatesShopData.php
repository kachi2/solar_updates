<?php

namespace Tests\Concerns;

use App\Models\Admin;
use App\Models\Category;
use App\Models\CurrencyExchange;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\SubCategory;
use App\Models\User;

/**
 * Minimal records for feature tests (the legacy tables have no factories).
 */
trait CreatesShopData
{
    protected function createAdmin(array $attributes = []): Admin
    {
        return Admin::create($attributes + [
            'name' => 'Site Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret-password'),
        ]);
    }

    protected function createCustomer(array $attributes = []): User
    {
        return User::factory()->create($attributes + ['phone' => '0801'.random_int(1000000, 9999999)]);
    }

    protected function createProduct(array $attributes = []): Product
    {
        $category = Category::create(['name' => 'Inverters', 'image' => 'inverters.png']);
        // ProductController::store() assigns sub_category_id = 1 (kept from the old app).
        $subCategory = SubCategory::create(['category_id' => $category->id, 'name' => 'On/Off-Grid Inverter']);

        return Product::create($attributes + [
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
            'name' => 'Sample solar panel',
            'price' => 150,
            'sale_price' => 125.5,
            'image' => 'sample-panel.jpg',
            'status' => 0,
        ]);
    }

    protected function createExchangeRate(int $rate = 1500): CurrencyExchange
    {
        return CurrencyExchange::create(['currency' => 'USD', 'rate' => $rate]);
    }

    protected function createDefaultAddress(User $user, array $attributes = []): Shipping
    {
        return $user->shippings()->create($attributes + [
            'receiver_name' => $user->name,
            'receiver_email' => $user->email,
            'receiver_phone' => '8031234567',
            'address' => '1 Test Street',
            'city' => 'Ikeja',
            'state' => 'Lagos',
            'is_default' => 1,
        ]);
    }

    protected function createUnpaidOrder(User $user, float $amount = 251.0): Order
    {
        $shipping = $this->createDefaultAddress($user);
        $orderNo = (string) random_int(100000000, 999999999);

        OrderItem::create([
            'user_id' => $user->id,
            'order_No' => $orderNo,
            'product_name' => 'Sample solar panel',
            'qty' => 2,
            'price' => $amount / 2,
            'payable' => $amount,
        ]);

        return Order::create([
            'user_id' => $user->id,
            'order_No' => $orderNo,
            'payment_method' => 'card',
            'amount' => $amount,
            'shipping_id' => $shipping->id,
            'is_paid' => 0,
            'is_delivered' => 0,
            'dispatch_status' => 0,
        ]);
    }
}
