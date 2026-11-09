<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

class TransactionRepository
{
    public function paginateForUser(User $user, int $perPage): Paginator
    {
        return Transaction::where('user_id', $user->id)->latest()->simplePaginate($perPage);
    }

    public function existsByExternalRef(?string $externalRef): bool
    {
        return $externalRef !== null && $externalRef !== ''
            && Transaction::where('external_ref', $externalRef)->exists();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Transaction
    {
        return Transaction::create($attributes);
    }
}
