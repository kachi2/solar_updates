<?php

namespace App\Services;

use App\Exceptions\UserFacingException;
use App\Models\Shipping;
use App\Models\User;
use App\Repositories\AddressRepository;
use Illuminate\Support\Facades\DB;

/**
 * Address-book rules. Checkout always needs a default address, so:
 *  - a customer's first address becomes the default,
 *  - the default can only change by choosing another address,
 *  - the last address can't be removed, and removing the default promotes another.
 */
class AddressService
{
    public function __construct(private readonly AddressRepository $addresses) {}

    /**
     * @param  array<string, mixed>  $attributes  `shippings` columns
     */
    public function add(User $user, array $attributes, bool $makeDefault = false): Shipping
    {
        return DB::transaction(function () use ($user, $attributes, $makeDefault) {
            $makeDefault = $makeDefault || ! $this->addresses->hasDefault($user);

            if ($makeDefault) {
                $this->addresses->clearDefault($user);
            }

            return $this->addresses->create($user, $attributes + ['is_default' => $makeDefault ? 1 : 0]);
        });
    }

    /**
     * @param  array<string, mixed>  $attributes  `shippings` columns
     */
    public function update(User $user, Shipping $address, array $attributes, bool $makeDefault = false): Shipping
    {
        return DB::transaction(function () use ($user, $address, $attributes, $makeDefault) {
            $address->fill($attributes);

            if ($makeDefault && ! $address->isDefault()) {
                $this->addresses->clearDefault($user);
                $address->is_default = 1;
            }

            $address->save();

            return $address;
        });
    }

    public function setDefault(User $user, Shipping $address): void
    {
        DB::transaction(function () use ($user, $address) {
            $this->addresses->clearDefault($user);
            $address->update(['is_default' => 1]);
        });
    }

    /**
     * @throws UserFacingException when it is the customer's only address
     */
    public function remove(User $user, Shipping $address): void
    {
        if ($this->addresses->countFor($user) <= 1) {
            throw new UserFacingException(
                'You need at least one delivery address. Add another address before removing this one.',
                'users.address',
            );
        }

        DB::transaction(function () use ($user, $address) {
            $wasDefault = $address->isDefault();
            $address->delete();

            if ($wasDefault) {
                $this->addresses->newestFor($user)?->update(['is_default' => 1]);
            }
        });
    }

    /**
     * Whether the customer may remove an address (they need at least one).
     */
    public function canRemove(User $user): bool
    {
        return $this->addresses->countFor($user) > 1;
    }
}
