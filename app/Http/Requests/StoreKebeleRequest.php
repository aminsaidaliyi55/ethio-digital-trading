<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKebeleRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:kebeles,name',
            'woreda_id' => 'required|exists:woredas,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The Kebele name is required.',
            'name.unique' => 'The Kebele name must be unique.',
            'woreda_id.required' => 'Please select a Woreda.',
            'woreda_id.exists' => 'The selected Woreda is invalid.',
        ];
    }
}
