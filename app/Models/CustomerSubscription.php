<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\CustomerSubscriptionStatus;


/**
 * @property int $id
 * @property CustomerSubscriptionStatus|int $status
 * @property Carbon|null $paid_at
 * @property Carbon|null $expires_at
 * @property Carbon $updated_at
 * @property string|null $stripe_id
 * @property-read Collection|Subscription|null $subscription
 * @property-read Collection|Customer|null $customer
 * @property-read Carbon $created_at
 * @property-read Carbon|null $deleted_at
 */
class CustomerSubscription extends Model
{
    use HasFactory, SoftDeletes;

    public const TABLE = 'customer_subscriptions';

    public const FIELD_ID = 'id';
    public const FIELD_STATUS = 'status';
    public const FIELD_PAID_AT = 'paid_at';
    public const FIELD_EXPIRES_AT = 'expires_at';
    public const FIELD_STRIPE_ID = 'stripe_id';

    public const RELATION_SUBSCRIPTION = 'subscription';
    public const RELATION_CUSTOMER = 'customer';

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
