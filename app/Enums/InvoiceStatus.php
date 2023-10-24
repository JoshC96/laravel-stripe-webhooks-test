<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case DRAFT = 0;
    case ISSUED = 1;
    case PAID = 2;
    case FAILED = 3;

    /**
     * @param int $value 
     * @return string|null 
     */
    public static function getDisplayString(int $value): ?string
    {
        return match ($value) {
            self::DRAFT->value => 'Draft',
            self::ISSUED->value => 'Issued',
            self::PAID->value => 'Paid',
            self::FAILED->value => 'Failed',
        };
    }
}