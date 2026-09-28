<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name',
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
