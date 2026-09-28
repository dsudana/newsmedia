<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|different:id',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori harus diisi',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'parent_id.exists' => 'Kategori parent tidak valid',
            'parent_id.different' => 'Kategori tidak bisa menjadi parent dari dirinya sendiri',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Kategori',
            'parent_id' => 'Kategori Parent',
            'description' => 'Deskripsi',
        ];
    }
}
