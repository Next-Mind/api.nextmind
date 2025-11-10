<?php

namespace App\Modules\Psychologists\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDisapprovePsychologistDocumentsFormRequest extends FormRequest
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
            'documents' => ['required', 'array', 'min:2'],
            'documents.*' => ['uuid', 'distinct', 'exists:psychologist_documents,id'],
            'rejection_reason' => ['required', 'string', 'min:15'],
        ];
    }
}
