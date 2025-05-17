<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => 'required|string|max:255|unique:shops,name', // Ensure the shop name is unique
            'latitude'       => 'required|numeric|between:-90,90', // Latitude is required and should be between valid range
            'longitude'      => 'required|numeric|between:-180,180', // Longitude is required and should be between valid range
            'owner_id'       => 'required|exists:users,id', // Owner must exist in users table
            'woreda_id'       => 'required|exists:woredas,id', // Owner must exist in users table
            'kebele_id'       => 'required|exists:kebeles,id', // Owner must exist in users table
            'phone'          => 'nullable|string|max:20|unique:shops,phone', // Ensure phone number is unique in shops table
            'website'        => 'nullable|url|max:255', // Optional but must be a valid URL
            'status'         => 'required|in:active,inactive', // Status must be either active or inactive
            'category_id'    => 'required|exists:categories,id', // Category ID must exist in categories table
            'shop_license'   => 'nullable|file|mimes:pdf|max:2048', // Ensure shop license is a valid PDF file
            'opening_hours'  => 'nullable|string|max:255', // Optional opening hours as a string
            'TIN'            => 'nullable|string|max:255|unique:shops,TIN', // Ensure TIN is unique
        ];
    }

    /**
     * Customize the error messages for the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The shop name is required.',
            'name.unique' => 'The shop name has already been taken. Please choose another name.',
            'latitude.required' => 'Latitude is required.',
            'latitude.numeric' => 'Latitude must be a number.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.required' => 'Longitude is required.',
            'longitude.numeric' => 'Longitude must be a number.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
            'owner_id.required' => 'An owner must be selected.',
            'owner_id.exists' => 'The selected owner does not exist in our records.',
            'phone.unique' => 'The phone number has already been taken.',
            'website.url' => 'Please provide a valid website URL.',
            'status.required' => 'The shop status is required.',
            'status.in' => 'The selected status is invalid. It must be either active or inactive.',
            'category_id.required' => 'A category is required.',
            'category_id.exists' => 'The selected category does not exist in our records.',
            'shop_license.file' => 'The shop license must be a file.',
            'shop_license.mimes' => 'The shop license must be a PDF file.',
            'shop_license.max' => 'The shop license file size cannot exceed 2MB.',
            'TIN.unique' => 'The TIN has already been taken. Please ensure the TIN is unique.',
        ];
    }
}
