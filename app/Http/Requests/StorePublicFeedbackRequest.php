<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'satisfaction' => ['required', 'string', 'in:like,dislike'],
            'category' => ['nullable', 'string', 'in:feedback,complaint,suggestion,concern,service,product,staff,facility,general'],
            'subject' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'recovery_expectation' => ['nullable', 'string', 'max:1000'],
            'service_highlight' => ['nullable', 'string', 'max:1000'],
            'follow_up_channel' => ['nullable', 'string', 'in:none,email,phone'],
            'hci_context' => ['nullable', 'json', 'max:5000'],
            'photos' => ['nullable', 'array', 'max:1'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'satisfaction.required' => 'Please choose Good or Not Good.',
            'photos.max' => 'You may only upload 1 photo.',
        ];
    }
}
