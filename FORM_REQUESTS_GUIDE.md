# Form Requests Validation Guide

## Overview

Form Requests adalah **dedicated classes untuk validasi input** pada controller actions. Benefits:

- **Separation of Concerns**: Validasi logic terpisah dari controller logic
- **Reusability**: Validasi rules bisa dipakai di multiple places (API, Web, Commands)
- **Testability**: Mudah test validation rules tanpa hitting controller
- **Maintainability**: Perubahan validation rules hanya di satu tempat
- **Authorization**: Built-in `authorize()` method untuk permission checks

## Form Request Classes

### StoreArticleRequest
Untuk membuat artikel baru.

```php
use App\Http\Requests\StoreArticleRequest;

public function store(StoreArticleRequest $request)
{
    $validated = $request->validated(); // Sudah di-validate
    // Process data...
}
```

**Rules:**
- `title`: required, max 255
- `category_id`: required, exists in categories
- `content`: required
- `featured_image`: nullable, image, max 2MB
- `status`: required, in (draft, published, scheduled, archived)
- `tags`, `keywords`: optional arrays

**Custom Messages:**
```json
{
  "title.required": "Judul artikel harus diisi",
  "category_id.required": "Kategori harus dipilih"
}
```

### UpdateArticleRequest
Untuk update artikel (includes FAQs).

```php
use App\Http\Requests\UpdateArticleRequest;

public function update(UpdateArticleRequest $request, Article $article)
{
    $validated = $request->validated();
    // Process with same rules + FAQs validation
}
```

**Additional Rules:**
- `faqs`: optional array
- `faqs.*.question`: required if faqs present
- `faqs.*.answer`: required if faqs present

### StoreCategoryRequest
Untuk membuat kategori baru.

```php
use App\Http\Requests\StoreCategoryRequest;

public function store(StoreCategoryRequest $request)
{
    $validated = $request->validated();
}
```

**Rules:**
- `name`: required, max 255
- `parent_id`: optional, exists in categories
- `description`: optional string
- `meta_title`: optional, max 255
- `meta_description`: optional, max 255

### UpdateCategoryRequest
Update kategori dengan extra validation.

```php
use App\Http\Requests\UpdateCategoryRequest;

public function update(UpdateCategoryRequest $request, Category $category)
{
    $validated = $request->validated();
}
```

**Additional Rules:**
- `parent_id`: cannot be same as current category (prevents circular reference)

### StoreTagRequest
Untuk membuat tag baru.

```php
use App\Http\Requests\StoreTagRequest;

public function store(StoreTagRequest $request)
{
    $validated = $request->validated();
}
```

**Rules:**
- `name`: required, max 255, unique

### UpdateTagRequest
Update tag dengan unique validation.

```php
use App\Http\Requests\UpdateTagRequest;

public function update(UpdateTagRequest $request, Tag $tag)
{
    $validated = $request->validated();
}
```

## Creating Form Requests

### Step 1: Generate Class

```bash
php artisan make:request StoreProductRequest
```

This creates: `app/Http/Requests/StoreProductRequest.php`

### Step 2: Define Rules & Authorization

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    // Authorization: who can make this request?
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users
    }

    // Validation rules
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    // Custom error messages (optional)
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
        ];
    }

    // Custom attribute names (optional)
    public function attributes(): array
    {
        return [
            'name' => 'Nama Produk',
            'price' => 'Harga',
        ];
    }
}
```

### Step 3: Use in Controller

```php
use App\Http\Requests\StoreProductRequest;

public function store(StoreProductRequest $request)
{
    // Validation sudah dilakukan otomatis
    // Jika fail, akan redirect dengan error messages
    
    $validated = $request->validated(); // Get validated data
    
    // Process...
}
```

## Authorization Checks

Form Requests punya built-in authorization:

```php
public function authorize(): bool
{
    // Return true jika user boleh melakukan action ini
    return auth()->check() && auth()->user()->can('create-articles');
}

// Jika false, throw 403 Unauthorized
```

## Custom Validation Rules

### Built-in Rules:

```php
'email' => 'required|email|unique:users,email',
'age' => 'required|integer|min:18|max:100',
'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
'date' => 'required|date|after:today',
'phone' => 'required|regex:/^[0-9]{10,}$/',
'status' => 'required|in:active,inactive,pending',
```

### Custom Rule Examples:

```php
public function rules(): array
{
    return [
        'slug' => 'required|unique:articles,slug,' . $this->article->id,
        // Unique except for current article
        
        'parent_id' => 'nullable|not_in:' . $this->category->id,
        // Parent cannot be itself
        
        'email' => 'required|email|unique:users,email',
        // Must be unique email
    ];
}
```

## Before/After Validation Hooks

```php
protected function prepareForValidation(): void
{
    // Manipulate input before validation
    $this->merge([
        'slug' => \Str::slug($this->input('title')),
    ]);
}

protected function passedValidation(): void
{
    // Run custom logic after validation passes
    $this->merge([
        'user_id' => auth()->id(),
    ]);
}
```

## Conditional Rules

```php
public function rules(): array
{
    return [
        'status' => 'required|in:draft,published,scheduled',
        'published_at' => $this->input('status') === 'published' 
            ? 'required|date' 
            : 'nullable|date',
    ];
}
```

## Testing Form Requests

```php
use Tests\TestCase;

class StoreArticleRequestTest extends TestCase
{
    public function test_valid_article_data()
    {
        $request = new StoreArticleRequest([
            'title' => 'Test Article',
            'content' => 'Lorem ipsum...',
            'category_id' => 1,
            'status' => 'published',
        ]);

        $this->assertTrue($request->authorize());
        $this->assertEmpty($request->validator()->errors());
    }

    public function test_missing_required_fields()
    {
        $request = new StoreArticleRequest([]);
        
        $this->assertFalse($request->validator()->passes());
    }
}
```

## Error Response Examples

### Failed Validation (HTML):
```
Redirect to previous page with errors:
- title: "Judul artikel harus diisi"
- category_id: "Kategori harus dipilih"
```

### Failed Validation (JSON API):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["Judul artikel harus diisi"],
    "category_id": ["Kategori harus dipilih"]
  }
}
```

### Failed Authorization:
```
HTTP 403 Forbidden
```

## Migration from Old Pattern

### Before (Validation in Controller):
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
    ]);
}
```

### After (Form Request):
```php
public function store(StoreArticleRequest $request)
{
    $validated = $request->validated();
}
```

**Benefits:**
- ✅ Cleaner controller
- ✅ Reusable validation
- ✅ Testable separately
- ✅ Built-in auth checks
- ✅ Custom messages & attributes

## Best Practices

1. **One Form Request per Action**
   - StoreArticleRequest untuk store
   - UpdateArticleRequest untuk update
   - Jangan reuse

2. **Custom Messages in Indonesian**
   ```php
   'title.required' => 'Judul harus diisi',
   ```

3. **Use Attributes for Better Messages**
   ```php
   public function attributes(): array
   {
       return ['name' => 'Nama Produk'];
   }
   ```

4. **Validate at Input Boundary**
   - Form Requests handle web input
   - Use explicit validation untuk APIs

5. **Authorization + Validation**
   ```php
   public function authorize(): bool
   {
       return $this->user()->can('edit-articles');
   }
   ```

---

Form Requests membuat validasi **cleaner, reusable, dan professional**! 🚀
