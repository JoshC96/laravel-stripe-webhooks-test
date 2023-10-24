# Laravel Code Challenge: Controller Implementation for Stripe Subscription Webhooks

## Objective:
Develop a Laravel controller within a new Laravel project to handle Stripe webhooks pertaining to a multi-tiered subscription service, offering monthly, quarterly, and annual subscription plans.

## Background:
Imagine you're part of a team developing a Software as a Service (SaaS) platform. As a critical feature, the platform needs to offer a subscription-based payment system for its users. While a basic Laravel application is provided for reference, your task is to create a brand-new Laravel project that will be the foundation for this SaaS system's payment integration. This challenge focuses on the backend implementation, specifically on handling subscription-related webhook events from Stripe. Your goal is to ensure seamless payment experiences for users as they choose between different subscription durations.

## Project Setup:
### Initialize a new Laravel project.
Set up a database and configure the necessary environment settings.
Stripe Integration:

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

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
