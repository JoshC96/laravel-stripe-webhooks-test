<?php

namespace Database\Factories;

use App\Enums\CustomerSubscriptionStatus;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSubscription>
 */
class CustomerSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subscription = Subscription::inRandomOrder()->first();
        $statuses = array_column(CustomerSubscriptionStatus::cases(), 'value');
        $randomStatus = rand(0, count($statuses) - 1);
        $paid = fake()->dateTimeThisMonth();
        $expiry = fake()->dateTimeThisMonth();

        return [
            CustomerSubscription::FIELD_PAID_AT => $paid,
            CustomerSubscription::FIELD_EXPIRES_AT => $expiry,
            CustomerSubscription::FIELD_STATUS =>  $statuses[$randomStatus],
            CustomerSubscription::FIELD_SUBSCRIPTION_ID => $subscription->{Subscription::FIELD_ID}
        ];
    }
}
