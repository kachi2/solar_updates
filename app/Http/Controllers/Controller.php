<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;

abstract class Controller
{
    /**
     * Decrypt an id produced by encrypt() in the views; a tampered or invalid
     * value becomes a 404 instead of a 500.
     */
    protected function decryptId(string $value): mixed
    {
        try {
            return decrypt($value);
        } catch (DecryptException) {
            abort(404);
        }
    }
}
