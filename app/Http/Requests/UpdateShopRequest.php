<?php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Adjust this method based on your authorization logic.
        // For example, you might want to check if the user can update the specific shop.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Retrieve the shop ID from the route parameters
        $shopId = $this->route('shop'); 

        return [
            'name'           => 'required|string|max:255|unique:shops,name,' . $shopId, // Ensure name is unique for other shops
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'owner_id'       => 'nullable|exists:users,id', // Ensure the owner exists in the users table
            'woreda_id'       => 'nullable|exists:woredas,id', // Ensure the owner exists in the users table
            'kebele_id'       => 'nullable|exists:kebeles,id', // Ensure the owner exists in the users table
            'email'          => 'nullable|email|max:255|unique:shops,email,' . $shopId, // Ensure email is unique, ignoring current shop's email
            'phone'          => 'nullable|string|max:20|unique:shops,phone,' . $shopId, // Ensure phone is unique, ignoring current shop's phone
            'website'        => 'nullable|url|max:255',
            'status'         => 'required|in:active,inactive', // Shop must have a valid status
                        'shop_license'   => 'nullable|file|mimes:pdf|max:2048', // Ensure shop license is a valid PDF file

            'category_id'    => 'required|exists:categories,id', // Ensure category exists in categories table
            'opening_hours'  => 'nullable|string|max:255',
            'TIN'            => 'nullable|string|max:20|unique:shops,TIN,' . $shopId, // Ensure TIN is unique, ignoring current shop's TIN
        ];
    }

    /**
     * Customize the error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name of the shop is required.',
            'name.unique' => 'The shop name has already been taken.',
            'latitude.numeric' => 'Latitude must be a number.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.numeric' => 'Longitude must be a number.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
            'owner_id.exists' => 'The selected owner does not exist.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone.unique' => 'The phone number has already been taken.',
            'status.required' => 'The shop status is required.',
            'status.in' => 'The selected status is invalid. It must be either active or inactive.',
            'category_id.required' => 'A category is required.',
            'category_id.exists' => 'The selected category does not exist in our records.',
            'TIN.unique' => 'The TIN has already been taken. Please ensure the TIN is unique.',
        ];
    }
}
