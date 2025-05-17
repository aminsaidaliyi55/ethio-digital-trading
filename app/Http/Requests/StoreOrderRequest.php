<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function messages()
{
    return [
        'user_id.required' => 'The user field is required.',
        'user_id.exists' => 'The selected user does not exist.',
        'product_id.required' => 'Please select a product.',
        'product_id.exists' => 'The selected product does not exist.',
        'quantity.required' => 'Quantity is required.',
        'quantity.integer' => 'Quantity must be an integer.',
        'quantity.min' => 'Quantity must be at least 1.',
        'total_price.required' => 'Total price is required.',
        'total_price.numeric' => 'Total price must be a number.',
        'total_price.min' => 'Total price cannot be negative.',
    ];
}

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'nullable|string',
        ];
    }
}
