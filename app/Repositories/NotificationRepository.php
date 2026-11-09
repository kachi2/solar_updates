<?php

namespace App\Repositories;

use App\Models\AdminNotify;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Customer notifications (`notifications`) and admin alerts (`admin_notifies`).
 */
class NotificationRepository
{
    // ---- Customer notifications ---------------------------------------------

    /**
     * @return Collection<int, Notification>
     */
    public function forUser(User $user): Collection
    {
        return $user->userNotifications()->latest()->get();
    }

    public function createForUser(User $user, string $title, string $message): Notification
    {
        return Notification::create(['user_id' => $user->id, 'title' => $title, 'message' => $message]);
    }

    public function deleteForUser(User $user, int|string $id): void
    {
        $user->userNotifications()->whereKey($id)->delete();
    }

    // ---- Admin alerts ---------------------------------------------------------

    public function createAdminAlert(string $message): AdminNotify
    {
        return AdminNotify::create(['message' => $message]);
    }

    /**
     * @return Collection<int, AdminNotify>
     */
    public function unreadAdminAlerts(int $limit): Collection
    {
        return AdminNotify::where('is_read', 0)->latest()->take($limit)->get();
    }

    /**
     * @return Collection<int, AdminNotify>
     */
    public function readAdminAlerts(int $limit): Collection
    {
        return AdminNotify::where('is_read', 1)->latest()->take($limit)->get();
    }
}
