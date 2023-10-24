<?php

namespace Database\Factories;

use App\Enums\SubscriptionFrequency;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = array_column(SubscriptionStatus::cases(), 'value');
        $randomStatus = rand(0, count($statuses) - 1);
        $frequencies = array_column(SubscriptionFrequency::cases(), 'value');
        $randomFrequency = rand(0, count($frequencies) - 1);

        return [
            Subscription::FIELD_NAME => fake()->name(),
            Subscription::FIELD_STATUS =>  $statuses[$randomStatus],
            Subscription::FIELD_FREQUENCY => $frequencies[$randomFrequency],
            Subscription::FIELD_STRIPE_ID => 'sub_' . fake()->uuid(),
        ];
    }
}
