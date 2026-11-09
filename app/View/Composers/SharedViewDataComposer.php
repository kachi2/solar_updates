<?php

namespace App\View\Composers;

use App\Repositories\CategoryRepository;
use App\Repositories\ContentRepository;
use App\Repositories\NotificationRepository;
use Illuminate\View\View;

/**
 * Data the shop and admin layouts expect on every page (menus, categories,
 * admin notifications, exchange rate). Queried once per request.
 */
class SharedViewDataComposer
{
    /** @var array<string, mixed>|null */
    private ?array $data = null;

    public function __construct(
        private readonly ContentRepository $content,
        private readonly CategoryRepository $categories,
        private readonly NotificationRepository $notifications,
    ) {}

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
            // Menu items without a slug are skipped rather than breaking every page.
            'menu' => $this->content->linkableMenus(),
            'menu_categories' => $this->categories->allWithProductCounts(),
            'unread_notify' => $this->notifications->unreadAdminAlerts(10),
            'read_notify' => $this->notifications->readAdminAlerts(2),
            'dollar_exchange_rate' => $this->content->exchangeRate(),
        ];
    }
}
