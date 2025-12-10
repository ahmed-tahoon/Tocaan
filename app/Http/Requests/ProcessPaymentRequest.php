<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_id' => 'sometimes|string',
            'payment_method' => 'required|string',
            'gateway' => 'required|string|in:credit_card,paypal',
            'card_number' => 'required_if:gateway,credit_card|string',
            'email' => 'required_if:gateway,paypal|email',
        ];
    }
}
