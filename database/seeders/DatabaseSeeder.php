<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Subscription::factory(5);

        Customer::factory(5)
            ->has(CustomerSubscription::factory()->count(5), Customer::RELATION_CUSTOMER_SUBSCRIPTIONS)
            ->has(Invoice::factory()->count(5), Invoice::RELATION_CUSTOMER)
            ->create();

    }
}
