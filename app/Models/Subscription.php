<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $frequency
 * @property Carbon $updated_at
 * @property-read Carbon $created_at
 * @property-read Carbon|null $deleted_at
 */
class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    public const TABLE = 'subscriptions';

    public const FIELD_ID = 'id';
    public const FIELD_NAME = 'name';
    public const FIELD_STATUS = 'status';
    public const FIELD_FREQUENCY = 'frequency';

    public const RELATION_CUSTOMER_SUBSCRIPTIONS = 'customer_subscriptions';

    /**
     * @return HasMany 
     */
    public function customerSubscriptions(): HasMany
    {
        return $this->hasMany(CustomerSubscription::class);
    }

}
