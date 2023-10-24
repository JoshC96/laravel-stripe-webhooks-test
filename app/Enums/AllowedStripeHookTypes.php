<?php

namespace App\Enums;

enum AllowedStripeHookTypes: int
{
    case INVOICE_PAID = "invoice.paid";
    case PAYMENT_FAILED = "invoice.payment_failed";
    case SUBSCRIPTION_DELETED = "customer.subscription.deleted";

    /**
     * @param int $value 
     * @return string|null 
     */
    public static function getDisplayString(int $value): ?string
    {
        return match ($value) {
            self::INVOICE_PAID->value => 'Invoice Paid',
            self::PAYMENT_FAILED->value => 'Invoice Payment Failed',
            self::SUBSCRIPTION_DELETED->value => 'Subscription Deleted',
        };
    }
}
