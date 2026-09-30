<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $this->route('article')->id,
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'scheduled_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'focus_keyword' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'keywords' => 'nullable|array',
            'keywords.*' => 'exists:keywords,id',
            'is_featured' => 'boolean',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string',
            'faqs.*.answer' => 'required_with:faqs|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul artikel harus diisi',
            'title.max' => 'Judul artikel maksimal 255 karakter',
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'content.required' => 'Konten artikel harus diisi',
            'featured_image.image' => 'File featured image harus berupa gambar',
            'featured_image.max' => 'Ukuran featured image maksimal 2MB',
            'status.required' => 'Status artikel harus dipilih',
            'status.in' => 'Status artikel tidak valid',
            'tags.array' => 'Tags harus berupa array',
            'tags.*.exists' => 'Salah satu tag tidak valid',
            'faqs.*.question.required_with' => 'Pertanyaan FAQ harus diisi',
            'faqs.*.answer.required_with' => 'Jawaban FAQ harus diisi',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul Artikel',
            'category_id' => 'Kategori',
            'content' => 'Konten',
            'featured_image' => 'Gambar Utama',
            'status' => 'Status',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
        ];
    }
}
