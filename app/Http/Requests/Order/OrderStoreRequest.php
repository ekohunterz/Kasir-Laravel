<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'grand_total' => 'required|integer',
            'cash' => 'required|integer|gte:grand_total',
            'payment_id' => 'required|exists:payments,id',
            'already_paid' => 'required|boolean',
            'note' => 'nullable|string',
            'discount' => 'nullable|integer|min:0',
        ];
    }
}
