<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_ref',
        'external_ref',
        'type',
        'payment_method',
        'order_No',
        'prev_balance',
        'avail_balance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactionType(): TransactionType
    {
        return TransactionType::fromLegacy($this->type);
    }

    public function paymentMethodLabel(): string
    {
        return PaymentMethod::labelFor($this->payment_method);
    }
}
