# Controller Implementation for Stripe Subscription Webhooks

## Objective:
Develop a Laravel controller within a new Laravel project to handle Stripe webhooks pertaining to a multi-tiered subscription service, offering monthly, quarterly, and annual subscription plans.

## Project Setup
The project can be checked out locally and setup simply with `composer install` and `npm install`. Ensure a database connection is created in the .env file.

The skeleton project has been generated using Laravel Inertia and Breeze.  

### Seeding
The project can be kickstarted locally by seeding with `php artisan db:seed`. This is test data generated with Faker methods. 

### Testing
Ensure the application is working as expected by running `php artisan test`. Database seeding is not required for this to run. 


# Project Requirements:
### Initialize a new Laravel project.
Set up a database and configure the necessary environment settings.

### Integrate Stripe into your new project.
Implement a StripeWebhookController to handle incoming Stripe events.
This controller should handle at least these Stripe events:
invoice.paid: Update the user's subscription status and expiration date in the local database.
invoice.payment_failed: Notify the user (can be a log entry or database flag) that their payment failed.
customer.subscription.deleted: Update the user's status to inactive in the local database.
 
### Database:
Create or extend tables to manage subscription-related data: subscription_id, subscription_status, subscription_plan, expires_at, etc.
Ensure that the database interactions are transactional where necessary.

### Validation & Error Handling:
Within the controller, validate the webhook payload to ensure it's genuinely coming from Stripe.
Handle potential exceptions or errors, ensuring that the system behaves gracefully.

### Testing:
Use Laravel's testing tools to mock Stripe webhook events and test your StripeWebhookController for the events mentioned above.