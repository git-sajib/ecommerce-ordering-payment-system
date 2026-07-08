<?php

namespace App\Http\Requests\Payment;

use App\Enums\PaymentProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'exists:orders,id',
            ],

            'provider' => [
                'required',
                new Enum(PaymentProvider::class),
            ],
        ];
    }
}
