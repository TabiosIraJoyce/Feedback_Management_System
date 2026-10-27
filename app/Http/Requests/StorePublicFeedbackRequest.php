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
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['required', 'email', 'max:255'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'satisfaction' => ['required', 'string', 'in:like,dislike'],
            'category' => ['nullable', 'string', 'in:feedback,complaint,suggestion,concern,service,product,staff,facility,general'],
            'subject' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'recovery_expectation' => ['nullable', 'string', 'max:1000'],
            'service_highlight' => ['nullable', 'string', 'max:1000'],
            'follow_up_channel' => ['nullable', 'string', 'in:none,email,phone'],
            'hci_context' => ['nullable', 'json', 'max:5000'],
            'photos' => ['required', 'array', 'min:1', 'max:1'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'satisfaction.required' => 'Please choose Good or Not Good.',
            'customer_name.required' => 'Please enter your name.',
            'customer_phone.required' => 'Please enter your contact number.',
            'customer_email.required' => 'Please enter your email address.',
            'age.required' => 'Please enter your age.',
            'age.integer' => 'Please enter a valid age.',
            'age.min' => 'Age must be at least 1.',
            'age.max' => 'Age must be 120 or below.',
            'description.required' => 'Please describe your experience.',
            'rating.required' => 'Please select a star rating.',
            'photos.required' => 'Please upload or take a photo.',
            'photos.min' => 'Please upload or take a photo.',
            'photos.max' => 'You may only upload 1 photo.',
        ];
    }
}
