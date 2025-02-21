<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'short_name'    => 'nullable|string|max:50',
            'description'   => 'nullable|string',
            'address'       => 'nullable|string',
            'phone'         => 'required|string|max:20',
            'shop_name'     => 'required|string|max:255',
            'tax'           => 'required|numeric|min:0',
            'favicon'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
