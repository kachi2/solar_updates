<?php

namespace App\Repositories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    // ---- Customer views -----------------------------------------------------

    public function paginateForUser(User $user, int $perPage): LengthAwarePaginator
    {
        return Order::where('user_id', $user->id)->with('items')->latest()->paginate($perPage);
    }

    /**
     * @return Collection<int, Order>
     */
    public function recentForUser(User $user, int $limit): Collection
    {
        return Order::where('user_id', $user->id)->with('items')->latest()->take($limit)->get();
    }

    public function countForUser(User $user): int
    {
        return Order::where('user_id', $user->id)->count();
    }

    public function findForUserOrFail(User $user, int|string $orderNo): Order
    {
        return Order::where('order_No', $orderNo)->where('user_id', $user->id)->firstOrFail();
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function itemsForUser(User $user, int|string $orderNo): Collection
    {
        return OrderItem::where('order_No', $orderNo)->where('user_id', $user->id)->get();
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function items(int|string $orderNo): Collection
    {
        return OrderItem::where('order_No', $orderNo)->get();
    }

    // ---- Checkout -----------------------------------------------------------

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function addItem(array $attributes): OrderItem
    {
        return OrderItem::create($attributes);
    }

    public function latestItemForUser(User $user): ?OrderItem
    {
        return OrderItem::where('user_id', $user->id)->latest()->first();
    }

    public function existsByNumber(int|string $orderNo): bool
    {
        return Order::where('order_No', $orderNo)->exists();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Order
    {
        return Order::create($attributes);
    }

    public function latestUnpaidForUser(User $user): ?Order
    {
        return Order::where('user_id', $user->id)
            ->where('is_paid', PaymentStatus::Pending->value)
            ->latest()
            ->first();
    }
}
