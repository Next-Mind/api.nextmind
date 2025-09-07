<?php

namespace App\Http\Requests\Posts;

use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StorePostFormRequest extends FormRequest
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
            'title' => 'required|string|min:10|max:255',
            'subtitle' => 'sometimes|required|string|min:10|max:255',
            'post_category_id' => 'required|string|min:36',
            'image' => ['required',File::types(['jpg','jpeg','png'])->max(2 * 1024)],
            'body' => 'required|string',
            'language' => 'required|string',
            'reading_time' => 'sometimes|required|int|max:10',
            'visibility' => 'required|string'
        ];
    }
}
