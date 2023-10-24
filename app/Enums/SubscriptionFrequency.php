<?php

namespace App\Enums;

enum SubscriptionFrequency: int
{
    case MONTHLY = 1;
    case ANNUALLY = 2;

    /**
     * @param int $value 
     * @return string|null 
     */
    public static function getDisplayString(int $value): ?string
    {
        return match ($value) {
            self::MONTHLY->value => 'Monthly',
            self::ANNUALLY->value => 'Annually',
        };
    }
}