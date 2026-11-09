<?php

namespace App\Http\Controllers;

use App\Exceptions\UserFacingException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;

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

    /**
     * Send the customer back (or to the exception's route) with its message.
     * Flashed both as a form error and as `error`, which different pages display.
     */
    protected function failWith(UserFacingException $e): RedirectResponse
    {
        $redirect = $e->redirectRoute ? redirect()->route($e->redirectRoute) : back();

        return $redirect->withErrors($e->getMessage())->with('error', $e->getMessage());
    }
}
