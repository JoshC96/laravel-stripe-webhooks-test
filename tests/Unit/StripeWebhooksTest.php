<?php

namespace Tests\Unit;

use App\Enums\AllowedStripeHookTypes;
use App\Enums\CustomerSubscriptionStatus;
use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionFrequency;
use App\Enums\SubscriptionStatus;
use App\Http\Requests\StripeHookRequest;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Invoice;
use App\Models\Subscription;
use Carbon\Carbon;
use Database\Factories\InvoiceFactory;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use LogicException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use PHPUnit\Framework\ExpectationFailedException;
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

    /**
     * Create a new invoice in the Issued status and check that it is updated to the Failed status.
     * 
     * @return void 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     * @throws LogicException 
     * @throws BadRequestException 
     * @throws ExpectationFailedException 
     */
    public function test_payment_fails(): void
    {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create([
            Invoice::FIELD_STATUS => InvoiceStatus::ISSUED->value,
            Invoice::FIELD_CUSTOMER_ID => $customer->{Customer::FIELD_ID},
        ]);

        $testPayload = [
            StripeHookRequest::REQUEST_ID => fake()->uuid(),
            StripeHookRequest::REQUEST_API_VERSION => "2023-10-16",
            StripeHookRequest::REQUEST_OBJECT => 'event',
            StripeHookRequest::REQUEST_CREATED => 1680064028,
            StripeHookRequest::REQUEST_DATA => [
                'object' => [
                    'id' => $invoice->{Invoice::FIELD_STRIPE_ID},
                ]
            ],
            StripeHookRequest::REQUEST_TYPE => AllowedStripeHookTypes::PAYMENT_FAILED->value,
        ];

        $response = $this->post('/api/v1/stripe-hooks', $testPayload);
        $response->assertStatus(200);

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::FAILED->value, $invoice->{Invoice::FIELD_STATUS});
    }


    /**
     * Create a new CustomerSubscription and test the status is set to Active and expiry date is set to +1 month are updated.
     * Test the expiry time by creating a timestamp and subtracting an hour to brute-force cover any processing time.
     * 
     * @return void 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     * @throws LogicException 
     * @throws BadRequestException 
     * @throws ExpectationFailedException 
     */
    public function test_payment_succeeds(): void
    {
        $subscription = Subscription::factory()->create();
        $customer = Customer::factory()->create();
        $customerSubscription = CustomerSubscription::factory()->create([
            CustomerSubscription::FIELD_STATUS => CustomerSubscriptionStatus::INACTIVE->value,
            CustomerSubscription::FIELD_CUSTOMER_ID => $customer->{Customer::FIELD_ID},
            CustomerSubscription::FIELD_SUBSCRIPTION_ID => $subscription->{Subscription::FIELD_ID},
        ]);
        $testFutureTime = null;

        switch ($subscription->{Subscription::FIELD_FREQUENCY}) {
            case SubscriptionFrequency::ANNUALLY->value:
                $testFutureTime = Carbon::now()->addYear(1)->subHour();
                break;
            case SubscriptionFrequency::MONTHLY->value;
                $testFutureTime = Carbon::now()->addMonth(1)->subHour();
                break;
            default:
                throw new Exception('Subscription must have a frequency.');
        }


        $testPayload = [
            StripeHookRequest::REQUEST_ID => fake()->uuid(),
            StripeHookRequest::REQUEST_API_VERSION => "2023-10-16",
            StripeHookRequest::REQUEST_OBJECT => 'event',
            StripeHookRequest::REQUEST_CREATED => 1680064028,
            StripeHookRequest::REQUEST_DATA => [
                'object' => [
                    'id' => $subscription->{Subscription::FIELD_STRIPE_ID},
                    'customer' => $customer->{Customer::FIELD_STRIPE_ID},
                    'current_period_end' => $testFutureTime->timestamp
                ]
            ],
            StripeHookRequest::REQUEST_TYPE => AllowedStripeHookTypes::INVOICE_PAID->value,
        ];

        $response = $this->post('/api/v1/stripe-hooks', $testPayload);
        $response->assertStatus(200);

        $customerSubscription->refresh();
        
        $this->assertEquals(CustomerSubscriptionStatus::ACTIVE->value, $customerSubscription->{CustomerSubscription::FIELD_STATUS});
        $this->assertGreaterThanOrEqual($testFutureTime, $customerSubscription->{CustomerSubscription::FIELD_EXPIRES_AT});
    }


    /**
     * Create a new Customer in the Active status and then check they're set to the Inactive status.
     * 
     * @return void 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     * @throws LogicException 
     * @throws BadRequestException 
     * @throws ExpectationFailedException 
     */
    public function test_customer_subscription_can_be_set_inactive(): void
    {
        $subscription = Subscription::factory()->create();
        $customer = Customer::factory()->create();
        $customerSubscription = CustomerSubscription::factory()->create([
            CustomerSubscription::FIELD_STATUS => CustomerSubscriptionStatus::INACTIVE->value,
            CustomerSubscription::FIELD_CUSTOMER_ID => $customer->{Customer::FIELD_ID},
            CustomerSubscription::FIELD_SUBSCRIPTION_ID => $subscription->{Subscription::FIELD_ID},
        ]);

        $testPayload = [
            StripeHookRequest::REQUEST_ID => fake()->uuid(),
            StripeHookRequest::REQUEST_API_VERSION => "2023-10-16",
            StripeHookRequest::REQUEST_OBJECT => 'event',
            StripeHookRequest::REQUEST_CREATED => 1680064028,
            StripeHookRequest::REQUEST_DATA => [
                'object' => [
                    'id' => $subscription->{Subscription::FIELD_STRIPE_ID},
                    'customer' => $customer->{Customer::FIELD_STRIPE_ID}
                ]
            ],
            StripeHookRequest::REQUEST_TYPE => AllowedStripeHookTypes::SUBSCRIPTION_DELETED->value,
        ];

        $response = $this->post('/api/v1/stripe-hooks', $testPayload);
        $response->assertStatus(200);
        $customerSubscription->refresh();
        $this->assertEquals(CustomerSubscriptionStatus::INACTIVE->value, $customerSubscription->{CustomerSubscription::FIELD_STATUS});
    }



}