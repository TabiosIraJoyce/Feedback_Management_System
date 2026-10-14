<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')->ignore($this->product)],
            'description' => ['nullable', 'string', 'max:1000'],
            // Business types removed — accept nullable
            'business_type_id' => ['nullable'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'icon' => ['nullable', 'string', 'max:100'],
        ];
    }
}
