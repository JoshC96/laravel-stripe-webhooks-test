<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\CustomerStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $stripe_id
 * @property CustomerStatus|int $status
 * @property Carbon $updated_at
 * @property-read Collection|User|null $user
 * @property-read Carbon $created_at
 * @property-read Carbon|null $deleted_at
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public const TABLE = 'customers';

    public const FIELD_ID = 'id';
    public const FIELD_NAME = 'name';
    public const FIELD_EMAIL = 'email';
    public const FIELD_PHONE = 'phone';
    public const FIELD_STATUS = 'status';
    public const FIELD_USER_ID = 'user_id';
    public const FIELD_STRIPE_ID = 'stripe_id';

    public const RELATION_USER = 'user';
    public const RELATION_INVOICES = 'invoices';
    public const RELATION_CUSTOMER_SUBSCRIPTIONS = 'customerSubscriptions';

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany 
     */
    public function customerSubscriptions(): HasMany
    {
        return $this->hasMany(CustomerSubscription::class);
    }

    /**
     * @return HasMany 
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

}
