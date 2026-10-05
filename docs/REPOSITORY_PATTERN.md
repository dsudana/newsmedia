# Repository Pattern Implementation Guide

## Apa itu Repository Pattern?

Repository Pattern adalah design pattern yang memberikan **abstraction layer** antara aplikasi dan data source (database). Benefits:

- **Decoupling**: Controller tidak langsung query model, tapi melalui repository
- **Testability**: Mudah mock repository untuk unit testing
- **Maintainability**: Perubahan data access logic di satu tempat (repository)
- **Reusability**: Repository bisa digunakan di multiple controllers

## Struktur Repository

```
app/
├── Repositories/
│   ├── Contracts/
│   │   └── RepositoryInterface.php    # Base interface
│   ├── BaseRepository.php             # Base implementation
│   ├── ArticleRepository.php          # Article-specific
│   ├── CategoryRepository.php         # Category-specific
│   ├── TagRepository.php              # Tag-specific
│   └── UserRepository.php             # User-specific
└── Providers/
    └── RepositoryServiceProvider.php  # Service provider binding
```

## Menggunakan Repository

### 1. Dependency Injection di Controller

```php
namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

class ArticleController extends Controller
{
    protected $articleRepository;
    protected $categoryRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        // Gunakan repository
        $articles = $this->articleRepository->all(['category', 'user']);
        return view('articles.index', compact('articles'));
    }
}
```

### 2. Base Repository Methods (di semua repository)

```php
// Get all records
$articles = $articleRepository->all(['category', 'user']);

// Find by ID
$article = $articleRepository->find(1, ['category', 'user']);

// Find by column
$article = $articleRepository->findBy('slug', 'my-article');

// Paginate
$articles = $articleRepository->paginate(15, ['category']);

// Create
$article = $articleRepository->create([
    'title' => 'My Article',
    'category_id' => 1,
    'content' => 'Lorem ipsum...'
]);

// Update
$article = $articleRepository->update(1, ['title' => 'Updated Title']);

// Delete (soft delete)
$articleRepository->delete(1);

// Force delete (hard delete)
$articleRepository->forceDelete(1);

// Restore soft-deleted
$articleRepository->restore(1);

// Check existence
if ($articleRepository->exists('slug', 'my-article')) {
    // ...
}

// Count
$total = $articleRepository->count(['status' => 'published']);

// Where clause
$articles = $articleRepository->where('status', 'published', ['category'])->get();

// Filter (multiple conditions)
$articles = $articleRepository->filter([
    'status' => 'published',
    'category_id' => 1
], ['category'])->get();
```

### 3. Repository-Specific Methods (contoh ArticleRepository)

```php
// Get filtered articles dengan search, status, category
$articles = $articleRepository->getFiltered([
    'search' => 'Laravel',
    'status' => 'published',
    'category' => 1
], 15); // returns paginated result

// Create dengan relations (meta, tags, keywords)
$article = $articleRepository->createWithRelations(
    articleData: ['title' => '...', 'content' => '...'],
    metaData: ['meta_title' => '...'],
    tagIds: [1, 2, 3],
    keywordIds: [1, 2]
);

// Update dengan relations
$article = $articleRepository->updateWithRelations(
    id: 1,
    articleData: ['title' => 'Updated'],
    metaData: ['meta_title' => '...'],
    tagIds: [1, 2],
    keywordIds: [1],
    faqs: [...]
);

// Get published articles
$articles = $articleRepository->getPublished(10, ['category', 'user']);

// Get by category
$articles = $articleRepository->getByCategory(1, 10, ['user']);

// Bulk update
$articleRepository->bulkUpdate([1, 2, 3], ['status' => 'published']);

// Bulk delete
$articleRepository->bulkDelete([1, 2, 3]);

// Get popular (by views)
$articles = $articleRepository->getPopular(10, ['category']);

// Search advanced
$articles = $articleRepository->search('Laravel', [
    'status' => 'published',
    'category_id' => 1
], 15);
```

### 4. CategoryRepository Methods

```php
// Get active categories
$categories = $categoryRepository->getActive(['parent']);

// Get with article count
$categories = $categoryRepository->withCount(['articles']);

// Get parent categories
$parents = $categoryRepository->getParents();

// Get with children
$categories = $categoryRepository->getWithChildren();
```

### 5. TagRepository Methods

```php
// Get with article count
$tags = $tagRepository->withCount();

// Get popular tags
$tags = $tagRepository->getPopular(20);

// Find by slug
$tag = $tagRepository->findBySlug('laravel');
```

### 6. UserRepository Methods

```php
// Get active users
$users = $userRepository->getActive(['articles']);

// Get users with role
$admins = $userRepository->getUsersWithRole('admin');

// Get users with permission
$users = $userRepository->getUsersWithPermission('create-articles');

// Find by email
$user = $userRepository->findByEmail('john@example.com');

// Get with article count
$users = $userRepository->getWithArticleCount();
```

## Membuat Repository Baru

### 1. Create Repository Class

```php
<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    // Add custom methods untuk Product-specific logic
    public function getActive()
    {
        return $this->model->where('is_active', true)->get();
    }

    public function getByPriceRange($min, $max)
    {
        return $this->model
            ->whereBetween('price', [$min, $max])
            ->get();
    }
}
```

### 2. Register di RepositoryServiceProvider

```php
public function register(): void
{
    $this->app->bind(ProductRepository::class, function ($app) {
        return new ProductRepository($app->make('App\Models\Product'));
    });
}
```

### 3. Use di Controller

```php
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
}
```

## Best Practices

1. **Consistency**: Semua repository inherit dari `BaseRepository`
2. **Naming**: Repository class diawali dengan nama model (ArticleRepository, UserRepository)
3. **Location**: Semua di folder `app/Repositories/`
4. **Single Responsibility**: Repository hanya handle data access, bukan business logic
5. **Eager Loading**: Selalu specify relations yang dibutuhkan untuk avoid N+1
6. **Type Hints**: Return type jelas (Model, Collection, paginated result)

## Testing dengan Repository

Repository membuat testing jadi lebih mudah:

```php
use Tests\TestCase;
use App\Repositories\ArticleRepository;

class ArticleRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(ArticleRepository::class);
    }

    public function test_can_get_published_articles()
    {
        $articles = $this->repository->getPublished();
        $this->assertNotEmpty($articles);
    }
}
```

## Migration dari Direct Model Queries

**Before (without repository):**
```php
$articles = Article::where('status', 'published')
    ->with(['category', 'user'])
    ->latest()
    ->paginate(10);
```

**After (dengan repository):**
```php
$articles = $this->articleRepository->getPublished(10, ['category', 'user']);
```

Lebih clean, reusable, dan testable!

## Dokumentasi Lengkap

- **RepositoryInterface.php**: Base interface dengan semua method signature
- **BaseRepository.php**: Implementasi umum untuk CRUD operations
- **[ModelName]Repository.php**: Model-specific methods dan logic

---

Dengan Repository Pattern, codebase menjadi lebih maintainable, testable, dan scalable!
