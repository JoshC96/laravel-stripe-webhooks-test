<?php

namespace App\Enums;

enum SubscriptionFrequency: int
{
    case NONE = 0;
    case MONTHLY = 1;
    case ANNUALLY = 2;

    /**
     * @param int $value 
     * @return string|null 
     */
    public static function getDisplayString(int $value): ?string
    {
        return match ($value) {
            self::NONE->value => 'None',
            self::MONTHLY->value => 'Monthly',
            self::ANNUALLY->value => 'Annually',
        };
    }
}