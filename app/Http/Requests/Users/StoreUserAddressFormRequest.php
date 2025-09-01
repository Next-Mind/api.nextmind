<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserAddressFormRequest extends FormRequest
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
        return [
           'label' =>'required|string',
            'line1' =>'required|string',
            'line2' =>'required|string',
            'district' =>'required|string',
            'city' =>'required|string',
            'state' =>'required|string',
            'postal_code' =>'required',
            'country' =>'required|string',
            'is_primary' =>'required|boolean'
        ];
    }
}
