<?php

/*
|--------------------------------------------------------------------------
| Shopping cart (darryldecode/cart)
|--------------------------------------------------------------------------
| Values carried over from the old sofars app: the shop shows cart totals
| formatted as 1,234.00. Code that needs a raw number (e.g. the Flutterwave
| amount) calls getSubTotal(false).
*/

return [
    'format_numbers' => env('SHOPPING_FORMAT_VALUES', true),
    'decimals' => env('SHOPPING_DECIMALS', 2),
    'dec_point' => env('SHOPPING_DEC_POINT', '.'),
    'thousands_sep' => env('SHOPPING_THOUSANDS_SEP', ','),
    'storage' => null,
    'events' => null,
];
