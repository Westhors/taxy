<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending     = 'pending';       // بانتظار المعالجة (Pending)
    case Scheduled   = 'scheduled';     // مجدول (Scheduled)
    case Accepted    = 'accepted';      // تم القبول (Accepted)
    case PickedUp    = 'picked_up';     // تم الاستلام (Picked Up)
    case InTransit   = 'in_transit';    // في الطريق (In Transit)
    case Delivered   = 'delivered';     // تم التوصيل (Delivered)
    case Cancelled   = 'cancelled';     // تم الإلغاء (Cancelled)
    case Failed      = 'failed';        // فشل التوصيل (Failed)
    case Returned    = 'returned';      // تم الإرجاع (Returned)
    case Completed   = 'completed';     // مكتمل (Completed)

    /**
     * Get all values as array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get English label for each status.
     */
    public function enLabels(): string
    {
        return match ($this) {
            self::Pending     => 'Pending',
            self::Scheduled   => 'Scheduled',
            self::Accepted    => 'Accepted',
            self::PickedUp    => 'Picked Up',
            self::InTransit   => 'In Transit',
            self::Delivered   => 'Delivered',
            self::Cancelled   => 'Cancelled',
            self::Failed      => 'Failed',
            self::Returned    => 'Returned',
            self::Completed   => 'Completed',
        };
    }

    /**
     * Get Arabic label for each status.
     */
    public function arLabels(): string
    {
        return match ($this) {
            self::Pending     => 'بانتظار المعالجة',
            self::Scheduled   => 'مجدول',
            self::Accepted    => 'تم القبول',
            self::PickedUp    => 'تم الاستلام',
            self::InTransit   => 'في الطريق',
            self::Delivered   => 'تم التوصيل',
            self::Cancelled   => 'تم الإلغاء',
            self::Failed      => 'فشل التوصيل',
            self::Returned    => 'تم الإرجاع',
            self::Completed   => 'مكتمل',
        };
    }

    public function getLabel(): string
    {
        return match (app()->getLocale()) {
            'ar' => $this->arLabels(),
            default => $this->enLabels(),
        };
    }
}