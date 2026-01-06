<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zipcode' => ['required', 'string', 'max:10'],
            'country' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'string', 'in:razorpay,cod,card'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your full name',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Please provide a valid phone number (10-15 digits)',
            'address.required' => 'Shipping address is required',
            'city.required' => 'City is required',
            'zipcode.required' => 'ZIP/Postal code is required',
            'country.required' => 'Country is required',
            'payment_method.required' => 'Please select a payment method',
            'payment_method.in' => 'Invalid payment method selected',
        ];
    }
}