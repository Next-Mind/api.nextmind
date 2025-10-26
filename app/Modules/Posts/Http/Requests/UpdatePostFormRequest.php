<?php

namespace App\Modules\Posts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostFormRequest extends FormRequest
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
            'title' => 'sometimes|required|string|min:10|max:255',
            'subtitle' => 'sometimes|required|string|min:10|max:255',
            'post_category_id' => 'sometimes|required|string|min:36',
            'image'    => ['sometimes', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'body' => 'sometimes|required|string',
            'language' => 'sometimes|required|string',
            'reading_time' => 'sometimes|required|int|max:10',
            'visibility' => 'sometimes|required|string'
        ];
    }
}
