<?php

namespace Tests\Unit;

use App\Enums\AllowedStripeHookTypes;
use App\Http\Requests\StripeHookRequest;
use App\Models\Invoice;
use App\Models\Subscription;
use Tests\TestCase;

class StripeWebhooksTest extends TestCase
{

    public function test_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_the_api_webhook_route_exists(): void
    {
        $response = $this->get('/api/v1/stripe-hooks');

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Only the POST method is allowed.',
            ]);
    }

    public function test_validator_fails(): void
    {
        $response = $this->post('/api/v1/stripe-hooks');

        $response->assertStatus(302);
    }

    public function test_payment_fails(): void
    {
        $stripeId = Invoice::inRandomOrder()->first()->{Invoice::FIELD_STRIPE_ID};

        $testPayload = [
            StripeHookRequest::REQUEST_ID => fake()->uuid(),
            StripeHookRequest::REQUEST_API_VERSION => "2023-10-16",
            StripeHookRequest::REQUEST_OBJECT => 'event',
            StripeHookRequest::REQUEST_CREATED => 1680064028,
            StripeHookRequest::REQUEST_DATA => [
                'object' => [
                    'id' => $stripeId,
                ]
            ],
            StripeHookRequest::REQUEST_TYPE => AllowedStripeHookTypes::PAYMENT_FAILED->value,
        ];

        $response = $this->post('/api/v1/stripe-hooks', $testPayload);
        $response->assertStatus(200);
    }

}