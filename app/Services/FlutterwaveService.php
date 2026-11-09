<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    /**
     * Fetch a transaction from Flutterwave by its transaction id.
     *
     * Returns the transaction `data` payload, or null when Flutterwave cannot
     * find it or the request fails. Callers must still check the payload's
     * `status`, `amount` and `currency` before trusting the payment.
     *
     * @return array<string, mixed>|null
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        try {
            $response = Http::withToken(config('services.flutterwave.secret_key'))
                ->acceptJson()
                ->timeout(30)
                ->get(config('services.flutterwave.base_url').'/transactions/'.rawurlencode($transactionId).'/verify');
        } catch (ConnectionException $e) {
            Log::error('Flutterwave verify request failed', ['transaction_id' => $transactionId, 'error' => $e->getMessage()]);

            return null;
        }

        if ($response->failed() || $response->json('status') !== 'success') {
            Log::warning('Flutterwave verify returned an error', ['transaction_id' => $transactionId, 'body' => $response->json()]);

            return null;
        }

        return $response->json('data');
    }

    /**
     * Whether a verified transaction payload is a completed payment of at least
     * the expected amount in the configured currency.
     *
     * @param  array<string, mixed>  $transaction
     */
    public function isSuccessfulPayment(array $transaction, float $expectedAmount): bool
    {
        return ($transaction['status'] ?? null) === 'successful'
            && ($transaction['currency'] ?? null) === config('services.flutterwave.currency')
            && (float) ($transaction['amount'] ?? 0) >= $expectedAmount;
    }
}
