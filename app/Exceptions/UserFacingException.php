<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * A business-rule failure whose message is safe to show to the customer
 * (e.g. "Please select a default address"). Controllers catch it and
 * redirect back with the message as a form error.
 */
class UserFacingException extends RuntimeException
{
    /**
     * @param  string|null  $redirectRoute  Route to send the customer to, if not back().
     */
    public function __construct(string $message, public readonly ?string $redirectRoute = null)
    {
        parent::__construct($message);
    }
}
