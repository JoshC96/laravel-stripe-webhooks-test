<?php

namespace App\Enums;

enum SubscriptionStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    /**
     * @param int $value 
     * @return string|null 
     */
    public static function getDisplayString(int $value): ?string
    {
        return match ($value) {
            self::INACTIVE->value => 'Inactive',
            self::ACTIVE->value => 'Active',
        };
    }
}