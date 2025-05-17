<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFederalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
                $federalId = $this->route('federals'); // Retrieve the shop ID from the route parameters

        return [
            'name' => 'required|string|max:250|unique:roles,name,',
            'permissions' => 'required',
        ];
    }
}
