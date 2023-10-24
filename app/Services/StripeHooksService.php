<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Enums\AllowedStripeHookTypes;

class StripeHooksService
{

    public function __construct(
        protected CustomerRepository $customerRepository
    ) {}

    /**
     * Process the type of webhook request from Stripe and direct to the correct function.
     * 
     * @param array $data 
     * @param string $hookType 
     * @return bool 
     */
    public function handle(array $data, string $hookType): bool
    {
        switch ($hookType) {
            case AllowedStripeHookTypes::INVOICE_PAID->value: 
                return $this->paymentSuccess($data);
            case AllowedStripeHookTypes::PAYMENT_FAILED->value:
                return $this->paymentFailed($data);
            case AllowedStripeHookTypes::SUBSCRIPTION_DELETED->value:
                return $this->customerDeleted($data);
            default: 
                return false;
        }
    }


    /**
     * Update the user's subscription status and expiration date.
     * 
     * @param array $data 
     * @return bool
     */
    public function paymentSuccess(array $data): bool
    {
        return false;
    }


    /**
     * Notify the customer that their payment failed.
     * 
     * @param array $data 
     * @return bool
     */
    public function paymentFailed(array $data): bool
    {
        return false;
    }


    /**
     * Update the customer's status to inactive in the local database.
     * 
     * @param array $data 
     * @return bool
     */
    public function customerDeleted(array $data): bool
    {
        return false;
    }
}
