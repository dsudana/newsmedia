<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $tagId = $this->route('tag')->id;

        return [
            'name' => "required|string|max:255|unique:tags,name,{$tagId}",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tag harus diisi',
            'name.max' => 'Nama tag maksimal 255 karakter',
            'name.unique' => 'Nama tag sudah ada',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Tag',
        ];
    }
}
