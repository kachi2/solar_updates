<?php

/*
|--------------------------------------------------------------------------
| Sofar Solar shop settings
|--------------------------------------------------------------------------
| Business details and shop constants used across views, controllers and
| services. The WhatsApp number lives in config/services.php (whatsapp).
*/

return [

    'contact' => [
        'phones' => [
            ['display' => '0813 532 4241', 'tel' => '+2348135324241'],
            ['display' => '0906 945 4452', 'tel' => '+2349069454452'],
        ],
        'email' => 'support@sofarsolar.ng',
        'address' => 'Block C12, Shops 02 & 09, Arena Shopping Complex, Bolade-Oshodi, Lagos',
    ],

    'orders' => [
        // Prefix of payment references (Flutterwave tx_ref and admin-marked payments).
        'reference_prefix' => 'SFSL',
    ],

    // Request limits (see AppServiceProvider::configureRateLimits). Over the limit,
    // visitors get the "Too many requests" page (resources/views/errors/429.blade.php).
    'rate_limits' => [
        // Page views per visitor (product pages included). Admin pages, background
        // (AJAX) cart requests and the payment return page are not counted.
        'pages' => ['requests' => 10, 'minutes' => 1],
        'login' => ['attempts' => 3, 'minutes' => 5],
        'register' => ['attempts' => 3, 'minutes' => 5],
    ],

    // Items per page.
    'per_page' => [
        'home_products' => 50,
        'shop' => 20,
        'search' => 18,
        'orders' => 10,
        'payments' => 10,
        'addresses' => 12,
    ],

];
