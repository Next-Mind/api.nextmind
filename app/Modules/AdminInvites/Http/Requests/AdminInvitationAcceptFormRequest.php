<?php

namespace App\Modules\AdminInvites\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdminInvitationAcceptFormRequest extends FormRequest
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
            'token' => 'required|string|min:60|max:128',
            'name' => 'sometimes|required|string|min:3|max:255',
            'password' => ['sometimes', 'required', 'string', Password::min(8)->numbers()->letters()],
        ];
    }
}
