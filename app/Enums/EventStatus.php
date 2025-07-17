<?php

namespace App\Enums;

enum EventStatus: string
{
    case ACTIVE = 'Active';
    case PENDING = 'Pending';
    case CANCELLED = 'Cancelled';
    case SCHEDULED = 'Scheduled';

    public function eventStatus(string $name): string
    {
        return match ($name) {
            'Active' => self::ACTIVE->value,
            'Pending' => self::PENDING->value,
            'Cancelled' => self::CANCELLED->value,
            'Scheduled' => self::SCHEDULED->value,
            default => self::SCHEDULED->value,
        };
    }
}
