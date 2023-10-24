<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = array_column(InvoiceStatus::cases(), 'value');
        $randomStatus = rand(0, count($statuses) - 1);

        return [
            Invoice::FIELD_STATUS =>  $statuses[$randomStatus],
            Invoice::FIELD_TOTAL_PRICE => fake()->numberBetween(100, 1000),
            Invoice::FIELD_ISSUED_AT => Carbon::now(),
            Invoice::FIELD_PAID_AT => null,
            Invoice::FIELD_NOTE => fake()->paragraph(2),
        ];
    }
}
