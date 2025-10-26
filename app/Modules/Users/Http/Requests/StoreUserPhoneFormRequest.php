<?php

namespace App\Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method mixed route($key = null, $default = null)
 * @method mixed input($key = null, $default = null)
 */
class StoreUserPhoneFormRequest extends FormRequest
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
        $phoneId = $this->route('phone')?->getKey();
        return [
            'label' => 'required|string',
            'country_code' => 'required|integer',
            'area_code' => 'required|integer',
            'number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('user_phones', 'number')
                    ->where(
                        fn($q) => $q
                            ->where('country_code', $this->input('country_code'))
                            ->where('area_code',    $this->input('area_code'))
                    )
            ],
            'is_whatsapp' => 'required|boolean',
            'is_primary' => 'required|boolean'
        ];
    }
}
