<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\InvoiceStatus;

/**
 * @property int $id
 * @property InvoiceStatus|int $status
 * @property int $total_price
 * @property string $note
 * @property string $issued_at
 * @property string|null $paid_at
 * @property string|null $stripe_id
 * @property Carbon $updated_at
 * @property-read Collection|Customer|null $customer
 * @property-read Carbon $created_at
 * @property-read Carbon|null $deleted_at
 */
class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public const TABLE = 'customers';

    public const FIELD_ID = 'id';
    public const FIELD_STATUS = 'status';
    public const FIELD_TOTAL_PRICE = 'total_price';
    public const FIELD_NOTE = 'note';
    public const FIELD_ISSUED_AT = 'issued_at';
    public const FIELD_PAID_AT = 'paid_at';
    public const FIELD_CUSTOMER_ID = 'customer_id';
    public const FIELD_STRIPE_ID = 'stripe_id';

    public const RELATION_CUSTOMER = 'customer';

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
