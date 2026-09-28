# Custom Exception Handling Guide

## Overview

Project ini menggunakan **standardized custom exceptions** untuk error handling yang konsisten dan maintainable. Semua exceptions memiliki HTTP status codes dan dapat merespons baik JSON maupun HTML/redirect.

## Exception Classes

### 1. ValidationException (422 Unprocessable Entity)
Untuk validation errors pada input data.

**Usage:**
```php
use App\Exceptions\ValidationException;

// Single field error
throw ValidationException::singleField('email', 'Email sudah terdaftar');

// Multiple field errors
throw ValidationException::withErrors([
    'email' => 'Email sudah terdaftar',
    'username' => 'Username sudah diambil',
]);

// Manual message dengan errors
throw new ValidationException('Validasi gagal', [
    'email' => 'Email tidak valid',
]);
```

**Response (JSON):**
```json
{
  "success": false,
  "message": "Validasi data gagal",
  "errors": {
    "email": "Email sudah terdaftar",
    "username": "Username sudah diambil"
  }
}
```

### 2. ResourceNotFoundException (404 Not Found)
Ketika resource yang diminta tidak ada.

**Usage:**
```php
use App\Exceptions\ResourceNotFoundException;

// Generic resource not found
throw ResourceNotFoundException::create('Article', 1);

// Output: "Article dengan identifier '1' tidak ditemukan"
```

**Response (JSON):**
```json
{
  "success": false,
  "message": "Article dengan identifier '1' tidak ditemukan"
}
```

### 3. ArticleException (varies: 404, 409, 403, 422)
Domain-specific exceptions untuk article operations.

**Usage:**
```php
use App\Exceptions\ArticleException;

// Article not found
throw ArticleException::notFound(1);

// Article already published
throw ArticleException::alreadyPublished(5);

// Cannot delete article
throw ArticleException::cannotDelete(3, 'Masih ada komentar aktif');

// Invalid status
throw ArticleException::invalidStatus('pending');

// Unauthorized access
throw ArticleException::unauthorizedAccess($articleId, $userId);

// Invalid metadata
throw ArticleException::invalidMetadata('Meta title terlalu panjang');
```

### 4. CategoryException (varies: 404, 409, 422)
Domain-specific exceptions untuk category operations.

**Usage:**
```php
use App\Exceptions\CategoryException;

// Category not found
throw CategoryException::notFound(1);

// Duplicate slug
throw CategoryException::duplicateSlug('technology');

// Cannot delete with articles
throw CategoryException::cannotDeleteWithArticles(5, 12);

// Invalid parent
throw CategoryException::invalidParent(999);

// Circular reference
throw CategoryException::circularReference(1, 2);
```

### 5. UnauthorizedException (403 Forbidden)
Ketika user tidak memiliki akses/permission.

**Usage:**
```php
use App\Exceptions\UnauthorizedException;

// Generic unauthorized
throw new UnauthorizedException('Akses ditolak');

// Missing permission
throw UnauthorizedException::missingPermission('create-articles');

// Missing role
throw UnauthorizedException::missingRole('admin');

// Resource owner only
throw UnauthorizedException::resourceOwnerOnly('artikel');
```

### 6. BusinessLogicException (varies: 400, 409)
Untuk business logic errors yang tidak cocok dengan kategori lain.

**Usage:**
```php
use App\Exceptions\BusinessLogicException;

// Generic conflict
throw BusinessLogicException::conflict('Tidak bisa mengubah artikel yang sudah dipublikasi');

// Invalid operation
throw BusinessLogicException::invalidOperation(
    'publish_article',
    'Artikel harus memiliki featured image'
);

// State error
throw BusinessLogicException::stateError('published', 'draft');
```

### 7. RepositoryException (varies: 404, 422, 500)
Repository layer exceptions.

**Usage:**
```php
use App\Exceptions\RepositoryException;

// Model not found
throw RepositoryException::modelNotFound('Article', 1);

// Operation failed
throw RepositoryException::operationFailed('update', 'Article', 'Database connection error');

// Invalid data
throw RepositoryException::invalidData('Data tidak sesuai format yang diharapkan');
```

## Using in Controllers

### Pattern dengan Repository Exception Handling

```php
use App\Repositories\ArticleRepository;
use App\Exceptions\ArticleException;
use App\Exceptions\ValidationException;

class ArticleController extends Controller
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Check authorization
        if ($article->user_id !== auth()->id()) {
            throw ArticleException::unauthorizedAccess($article->id, auth()->id());
        }

        try {
            $updated = $this->articleRepository->update($article->id, $validated);
            return redirect()->route('articles.show', $updated)
                ->with('success', 'Artikel berhasil diperbarui');
        } catch (\Exception $e) {
            throw ArticleException::cannotDelete($article->id, $e->getMessage());
        }
    }

    public function publish(Article $article)
    {
        if ($article->status === 'published') {
            throw ArticleException::alreadyPublished($article->id);
        }

        if (!$article->featured_image) {
            throw BusinessLogicException::invalidOperation(
                'publish',
                'Artikel harus memiliki featured image'
            );
        }

        $article->update(['status' => 'published']);
        return redirect()->back()->with('success', 'Artikel dipublikasikan');
    }
}
```

## Exception Rendering Behavior

Exceptions dirender secara otomatis dalam `bootstrap/app.php`:

### Untuk JSON Requests:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {} // Jika ada validation errors
}
```

### Untuk HTML Requests:
- Redirect ke previous page dengan `error` session message
- Untuk UnauthorizedException, redirect ke home
- Browser akan menampilkan toast/alert dengan error message

## HTTP Status Codes

| Exception | Code | Usage |
|-----------|------|-------|
| ValidationException | 422 | Validation errors |
| ResourceNotFoundException | 404 | Resource not found |
| ArticleException | 404, 409, 403, 422 | Article-specific errors |
| CategoryException | 404, 409, 422 | Category-specific errors |
| UnauthorizedException | 403 | Permission denied |
| BusinessLogicException | 400, 409 | Business logic violations |
| RepositoryException | 404, 422, 500 | Repository-level errors |

## Best Practices

1. **Throw, don't return errors**
   ```php
   // ❌ Bad
   if (!$article) {
       return response()->json(['error' => 'Not found'], 404);
   }

   // ✅ Good
   if (!$article) {
       throw ArticleException::notFound($id);
   }
   ```

2. **Use specific exception classes**
   ```php
   // ❌ Bad
   throw new Exception('Article not found');

   // ✅ Good
   throw ArticleException::notFound($id);
   ```

3. **Provide context in error messages**
   ```php
   // ❌ Bad
   throw ArticleException::cannotDelete($id);

   // ✅ Good
   throw ArticleException::cannotDelete($id, 'Masih ada komentar aktif');
   ```

4. **Check business logic before operations**
   ```php
   // ✅ Good
   if ($article->status === 'published') {
       throw ArticleException::alreadyPublished($article->id);
   }
   ```

5. **Catch and re-throw with context**
   ```php
   try {
       // Database operation
   } catch (\Exception $e) {
       throw ArticleException::cannotDelete($id, $e->getMessage());
   }
   ```

## Testing with Exceptions

```php
use App\Exceptions\ArticleException;

public function test_article_not_found()
{
    $this->expectException(ArticleException::class);
    
    ArticleException::notFound(999);
}

public function test_unauthorized_access()
{
    $this->expectException(ArticleException::class);
    
    ArticleException::unauthorizedAccess($articleId, $otherUserId);
}
```

## Migration from Old Error Handling

### Before:
```php
$article = Article::find($id);
if (!$article) {
    return response()->json(['error' => 'Not found'], 404);
}
```

### After:
```php
$article = $this->articleRepository->find($id);
if (!$article) {
    throw ArticleException::notFound($id);
}
```

---

Dengan custom exceptions, error handling menjadi **consistent, maintainable, dan professional**! 🚀
