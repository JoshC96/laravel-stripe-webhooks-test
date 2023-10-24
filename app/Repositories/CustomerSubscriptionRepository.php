<?php

namespace App\Repositories;

use App\Enums\CustomerSubscriptionStatus;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Subscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CustomerSubscriptionRepository
{

    /**
     * @param array $hookData 
     * @return CustomerSubscription 
     * @throws InvalidArgumentException 
     * @throws Exception 
     */
    public function createFromStripeWebhook(array $hookData): CustomerSubscription
    {
        $customerId = Customer::query()
            ->where(Customer::FIELD_STRIPE_ID, Arr::get($hookData, 'data.object.customer'))
            ->pluck(Customer::FIELD_ID)
            ->first();

        $subscriptionId = Subscription::query()
            ->where(Subscription::FIELD_STRIPE_ID, Arr::get($hookData, 'data.object.id'))
            ->pluck(Subscription::FIELD_ID)
            ->first();

        $paidAt = Carbon::now();
        $expiresAt = Carbon::createFromTimestamp(Arr::get($hookData, 'data.object.current_period_end'));

        return $this->createCustomerSubscription(
            CustomerSubscriptionStatus::ACTIVE,
            $customerId,
            $subscriptionId,
            $paidAt,
            $expiresAt
        );
    }


    /**
     * @param array $data 
     * @return CustomerSubscription 
     * @throws Exception 
     */
    public function createCustomerSubscription(
        CustomerSubscriptionStatus $status,
        int $customerId,
        int $subscriptionId, 
        ?Carbon $paidAt = null, 
        ?Carbon $expiresAt = null
    ): CustomerSubscription {
        try { 
            DB::beginTransaction();
            $customerSubscription = CustomerSubscription::create([
                CustomerSubscription::FIELD_CUSTOMER_ID => $customerId,
                CustomerSubscription::FIELD_SUBSCRIPTION_ID => $subscriptionId,
                CustomerSubscription::FIELD_STATUS => $status->value,
                CustomerSubscription::FIELD_EXPIRES_AT => $expiresAt,
                CustomerSubscription::FIELD_PAID_AT => $paidAt,
            ]);

            $customerSubscription->save();
            DB::commit();

            return $customerSubscription;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }         
    }

    /**
     * @param CustomerSubscription $customerSubscription 
     * @param array $data 
     * @return bool 
     * @throws Exception 
     */
    public function updateCustomerSubscription(CustomerSubscription $customerSubscription, array $data): bool
    {
        try {
            DB::beginTransaction();
            $customerSubscription->fill($data);
            return $customerSubscription->save();
            DB::commit();

            return $customerSubscription;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }             
    }
}
