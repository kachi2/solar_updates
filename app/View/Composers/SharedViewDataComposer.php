<?php

namespace App\View\Composers;

use App\Models\AdminNotify;
use App\Models\Category;
use App\Models\CurrencyExchange;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\View\View;

/**
 * Data the shop and admin layouts expect on every page (menus, categories,
 * admin notifications, exchange rate). Queried once per request.
 */
class SharedViewDataComposer
{
    /** @var array<string, mixed>|null */
    private ?array $data = null;

    /**
     * Only fills keys the page did not set itself (e.g. the menu edit page
     * passes its own `$menu`, which must not be replaced by the menu list).
     */
    public function compose(View $view): void
    {
        foreach ($this->data() as $key => $value) {
            if (! $view->offsetExists($key)) {
                $view->with($key, $value);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function data(): array
    {
        return $this->data ??= [
            'menu' => Menu::all(),
            'menu_categories' => Category::all(),
            'product_menu' => Product::where('status', '!=', 1)->get(),
            'unread_notify' => AdminNotify::where('is_read', 0)->latest()->take(10)->get(),
            'read_notify' => AdminNotify::where('is_read', 1)->latest()->take(2)->get(),
            'dollar_exchange_rate' => CurrencyExchange::first(),
        ];
    }
}
