<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\MassAssignmentException;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\InvalidCastException;

class CustomerRepository
{

    /**
     * @param array $data 
     * @return Customer
     */
    public function createCustomer(array $data): Customer
    {
        $customer = new Customer();
        $customer->fill($data);
        $customer->save();

        return $customer;
    }

    /**
     * @param Customer $customer 
     * @param array $data 
     * @return bool 
     * @throws MassAssignmentException 
     * @throws InvalidArgumentException 
     * @throws InvalidCastException 
     */
    public function updateCustomer(Customer $customer, array $data): bool
    {
        $customer->fill($data);
        return $customer->save();
    }
}
