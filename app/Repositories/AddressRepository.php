<?php

namespace App\Repositories;

use App\Models\Shipping;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Customer delivery addresses (the `shippings` table).
 */
class AddressRepository
{
    public function defaultFor(User $user): ?Shipping
    {
        return $user->shippings()->default()->first();
    }

    /**
     * @return Collection<int, Shipping>
     */
    public function defaultsFor(User $user): Collection
    {
        return $user->shippings()->default()->get();
    }

    public function hasDefault(User $user): bool
    {
        return $user->shippings()->default()->exists();
    }

    /**
     * @return Collection<int, Shipping>
     */
    public function allFor(User $user): Collection
    {
        return $user->shippings()->get();
    }

    /**
     * Default address first, then newest.
     */
    public function paginateFor(User $user, int $perPage): LengthAwarePaginator
    {
        return $user->shippings()->orderByDesc('is_default')->latest()->paginate($perPage);
    }

    public function countFor(User $user): int
    {
        return $user->shippings()->count();
    }

    public function findForOrFail(User $user, int|string $id): Shipping
    {
        return $user->shippings()->findOrFail($id);
    }

    public function newestFor(User $user): ?Shipping
    {
        return $user->shippings()->latest()->first();
    }

    public function clearDefault(User $user): void
    {
        $user->shippings()->default()->update(['is_default' => 0]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(User $user, array $attributes): Shipping
    {
        return $user->shippings()->create($attributes);
    }
}
