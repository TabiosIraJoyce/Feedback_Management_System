<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage branches') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $branchId = $this->route('branch')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('branches', 'code')->ignore($branchId)],
            'description' => ['nullable', 'string'],
            // Business types removed — accept nullable
            'business_type_id' => ['nullable'],
            'address' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
        ];
    }
}
