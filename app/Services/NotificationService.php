<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\NotificationRepository;

/**
 * Customer notifications and admin dashboard alerts.
 */
class NotificationService
{
    public function __construct(private readonly NotificationRepository $notifications) {}

    public function welcome(User $user): void
    {
        $this->notifications->createForUser(
            $user,
            'welcome to sofarsolar',
            'Dear '.$user->name.', <br>We are glad to have you with us, do enjoy our services',
        );

        $this->alertAdmin('New Customer Registered');
    }

    public function alertAdmin(string $message): void
    {
        $this->notifications->createAdminAlert($message);
    }

    public function newOrderPlaced(string|int $orderNo): void
    {
        $this->alertAdmin('New customer order completed Order No: '.$orderNo);
    }
}
