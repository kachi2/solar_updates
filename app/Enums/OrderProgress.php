<?php

namespace App\Enums;

/**
 * The single delivery status a customer sees, derived from the order's
 * OrderStatus and DispatchStatus (see Order::progress()).
 */
enum OrderProgress: string
{
    case Processing = 'processing';
    case Dispatched = 'dispatched';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public static function for(OrderStatus $status, DispatchStatus $dispatch): self
    {
        return match (true) {
            $status === OrderStatus::Cancelled => self::Cancelled,
            $dispatch === DispatchStatus::Delivered => self::Delivered,
            $status === OrderStatus::Completed => self::Completed,
            $dispatch === DispatchStatus::Dispatched => self::Dispatched,
            default => self::Processing,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Processing => 'Processing',
            self::Dispatched => 'Dispatched',
            self::Delivered => 'Delivered',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Status pill style (CSS modifier of .sofar-status).
     */
    public function tone(): string
    {
        return match ($this) {
            self::Delivered, self::Completed => 'ok',
            self::Dispatched => 'info',
            self::Cancelled => 'danger',
            self::Processing => 'wait',
        };
    }
}
