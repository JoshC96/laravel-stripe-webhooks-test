<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Enums\AllowedStripeHookTypes;
use App\Enums\CustomerStatus;
use App\Enums\CustomerSubscriptionStatus;
use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\CustomerSubscriptionRepository;
use App\Repositories\InvoiceRepository;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class StripeHooksService
{

    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerSubscriptionRepository $customerSubscriptionRepository,
        protected InvoiceRepository $invoiceRepository
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
     * Update the customer's subscription status and expiration date.
     * 
     * @param array $data 
     * @return bool
     */
    public function paymentSuccess(array $data): bool
    {
        $customerSubscription = CustomerSubscription::query()
            ->whereHas(CustomerSubscription::RELATION_CUSTOMER, function (Builder $query) use ($data) {
                return $query->where(Customer::TABLE . '.' . Customer::FIELD_STRIPE_ID, Arr::get($data, 'data.object.customer'));
            })
            ->whereHas(CustomerSubscription::RELATION_SUBSCRIPTION, function (Builder $query) use ($data) {
                return $query->where(Subscription::TABLE . '.' . Subscription::FIELD_STRIPE_ID, Arr::get($data, 'data.object.id'));
            })
            ->first();

        if(!$customerSubscription) {
            $customerSubscription = $this->customerSubscriptionRepository->createFromStripeWebhook($data);
        } else {
            $paidAt = Carbon::now();
            $expiresAt = Carbon::createFromTimestamp(Arr::get($data, 'data.object.current_period_end'));

            return $this->customerSubscriptionRepository->updateCustomerSubscription($customerSubscription, [
                CustomerSubscription::FIELD_STATUS => CustomerSubscriptionStatus::ACTIVE->value,
                CustomerSubscription::FIELD_EXPIRES_AT => $expiresAt,
                CustomerSubscription::FIELD_PAID_AT => $paidAt,
            ]);
        }

        return true;
    }


    /**
     * Notify the customer that their payment failed.
     * 
     * @param array $data 
     * @return bool
     */
    public function paymentFailed(array $data): bool
    {
        $invoice = $this->invoiceRepository->findByStripeId(Arr::get($data, 'data.object.id'));
        $this->invoiceRepository->updateInvoice($invoice, [
            Invoice::FIELD_STATUS => InvoiceStatus::FAILED->value,
            Invoice::FIELD_PAID_AT => null
        ]);

        logger()->alert( sprintf('Payment for invoice with ID %s has failed.', $invoice->{Invoice::FIELD_STRIPE_ID}));

        return true;
    }


    /**
     * Update the customer's status to inactive. Could also be deleted as Customers use SoftDeletes
     * 
     * @param array $data 
     * @return bool
     */
    public function customerDeleted(array $data): bool
    {
        $customer = $this->customerRepository->findByStripeId(Arr::get($data, 'data.object.id'));

        $this->customerRepository->updateCustomer($customer, [
            Customer::FIELD_STATUS => CustomerStatus::INACTIVE->value
        ]);

        return true;
    }
}
