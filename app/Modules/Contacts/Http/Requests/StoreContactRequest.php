<?php

namespace App\Modules\Contacts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_id' => [
                'required',
                'uuid',
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
        ];
    }
}
