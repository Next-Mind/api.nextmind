<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StorePsychologistDocumentRequest extends FormRequest
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
            'crp_card'         => ['required', File::types(['pdf'])->max(2 * 1024)],
            'id_front'         => ['required', File::types(['pdf'])->max(2 * 1024)],
            'id_back'          => ['required', File::types(['pdf'])->max(2 * 1024)],
            'proof_of_address' => ['required', File::types(['pdf'])->max(2 * 1024)],
        ];
    }
}
