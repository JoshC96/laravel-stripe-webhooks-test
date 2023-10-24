<?php

namespace App\Repositories;

use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CustomerRepository
{

    /**
     * @param string $stripeId 
     * @return Customer 
     * @throws InvalidArgumentException 
     * @throws ModelNotFoundException 
     */
    public function findByStripeId(string $stripeId): Customer
    {
        return Customer::query()->where(Customer::FIELD_STRIPE_ID, $stripeId)->firstOrFail();
    }


    /**
     * @param array $data 
     * @return Customer 
     * @throws Exception 
     */
    public function createCustomer(array $data): Customer
    {
        try { 
            DB::beginTransaction();
            $customer = new Customer();
            $customer->fill($data);
            $customer->save();
            DB::commit();

            return $customer;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }         
    }

    /**
     * 
     * @param Customer $customer 
     * @param array $data 
     * @return bool 
     * @throws Exception 
     */
    public function updateCustomer(Customer $customer, array $data): bool
    {
        try {
            DB::beginTransaction();
            $customer->fill($data);
            return $customer->save();
            DB::commit();

            return $customer;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }             
    }
}
