<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StripeHookRequest extends FormRequest
{

    public const REQUEST_ID = 'id';
    public const REQUEST_OBJECT = 'object';
    public const REQUEST_API_VERSION = 'api_version';
    public const REQUEST_TYPE = 'type';
    public const REQUEST_CREATED = 'created';
    public const REQUEST_DATA= 'data';

    public const ALLOWED_TYPES = [
        "invoice.paid",
        "invoice.payment_failed",
        "customer.subscription.deleted",
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            self::REQUEST_ID => ['required', 'string'],
            self::REQUEST_OBJECT => ['required'],
            self::REQUEST_API_VERSION => ['required'],
            self::REQUEST_TYPE => ['required', Rule::in(self::ALLOWED_TYPES)],
            self::REQUEST_CREATED => ['required'],
        ];
    }
}
