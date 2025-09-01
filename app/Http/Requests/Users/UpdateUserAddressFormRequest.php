<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAddressFormRequest extends FormRequest
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
           'label' =>'sometimes|required|string',
            'line1' =>'sometimes|required|string',
            'line2' =>'sometimes|required|string',
            'district' =>'sometimes|required|string',
            'city' =>'sometimes|required|string',
            'state' =>'sometimes|required|string',
            'postal_code' =>'sometimes|required',
            'country' =>'sometimes|required|string',
            'is_primary' =>'sometimes|required|boolean'
        ];
    }
}
