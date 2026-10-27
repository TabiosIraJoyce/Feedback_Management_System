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
            'customer_phone' => ['required', 'string', 'max:50', 'regex:/^(?=(?:\D*\d){7})[0-9+\s().-]{7,20}$/'],
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
            'photos' => ['nullable', 'array', 'max:1'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'satisfaction.required' => 'Choose Good or Needs improvement to describe your overall experience.',
            'satisfaction.in' => 'Choose either Good or Needs improvement.',
            'customer_name.required' => 'Please enter your name.',
            'customer_phone.required' => 'Please enter your contact number.',
            'customer_phone.regex' => 'Enter a valid contact number with at least 7 digits. You may include +, spaces, parentheses, dots, or hyphens.',
            'customer_email.required' => 'Please enter your email address.',
            'customer_email.email' => 'Enter a valid email address, such as name@example.com.',
            'age.required' => 'Please enter your age.',
            'age.integer' => 'Please enter a valid age.',
            'age.min' => 'Age must be at least 1.',
            'age.max' => 'Age must be 120 or below.',
            'description.required' => 'Please describe your experience.',
            'description.max' => 'Please keep your description under 5,000 characters.',
            'rating.required' => 'Please select a star rating.',
            'rating.integer' => 'Select one whole star rating from 1 to 5.',
            'rating.min' => 'Select a rating from 1 to 5 stars.',
            'rating.max' => 'Select a rating from 1 to 5 stars.',
            'photos.max' => 'You may upload one photo at most.',
            'photos.*.image' => 'The selected file must be an image.',
            'photos.*.mimes' => 'Use a JPG, PNG, or WEBP image.',
            'photos.*.max' => 'The photo must be 4 MB or smaller.',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'name',
            'customer_phone' => 'contact number',
            'customer_email' => 'email address',
            'age' => 'age',
            'description' => 'description',
            'rating' => 'satisfaction rating',
            'photos.*' => 'photo',
        ];
    }
}
