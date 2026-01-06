<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->is_admin;
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
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99', 'gte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'gallery' => ['nullable', 'array', 'max:5'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'badge' => ['nullable', 'string', 'in:New,Sale,Hot,Limited'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom error messages for validator.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'price.required' => 'Product price is required',
            'price.min' => 'Price must be greater than 0',
            'original_price.gte' => 'Original price must be greater than or equal to current price',
            'stock.required' => 'Stock quantity is required',
            'stock.min' => 'Stock cannot be negative',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category does not exist',
            'image.image' => 'The file must be an image',
            'image.max' => 'Image size must not exceed 2MB',
            'gallery.max' => 'You can upload maximum 5 gallery images',
        ];
    }
}
