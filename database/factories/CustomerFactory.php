<?php

namespace Database\Factories;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = array_column(CustomerStatus::cases(), 'value');
        $randomStatus = rand(0, count($statuses) - 1);

        return [
            Customer::FIELD_NAME => fake()->name(),
            Customer::FIELD_EMAIL => fake()->unique()->safeEmail(),
            Customer::FIELD_PHONE => fake()->phoneNumber(),
            Customer::FIELD_STATUS =>  $statuses[$randomStatus],
            Customer::FIELD_STRIPE_ID => 'cus_' . fake()->uuid(),
        ];
    }
}
