# Homebuilder Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a drag-and-drop homepage builder system for customizing homepage (/) and blog index (/blog) with 8 article-focused section types, admin interface, and dynamic frontend rendering with fallback to existing hardcoded layout.

**Architecture:** Service-based design with `HomepageBuilderService` managing CRUD and data resolution. Single database table (`homepage_sections`) with `page_type` field to distinguish between pages. Admin UI provides drag-drop reordering and section-specific config modals. Frontend dynamically renders sections or falls back to hardcoded layout if empty.

**Tech Stack:** Laravel 11, PHP 8.3, Blade templating, Sortable.js (drag-drop), AJAX for admin forms, MySQL 8.0+

## Global Constraints

- Database table name: `homepage_sections` (singular)
- Field names: `page_type` (enum: 'homepage', 'blog_index'), `section_type`, `config` (JSON), `order` (integer)
- Service class: `App\Services\HomepageBuilderService`
- Model class: `App\Models\HomepageSection`
- Controller: `App\Http\Controllers\Admin\HomepageBuilderController`
- Route prefix: `/admin/homepage-builder`
- Section types: 8 total (article_carousel, category_highlight, newsletter, image_banner, text_image_split, blog_preview, testimonial, lookbook)
- Frontend partials location: `resources/views/frontend/sections/`
- No breaking changes to existing homepage (coexist strategy)

---

## File Structure

### New Files Created

```
app/
  ├── Http/Controllers/Admin/HomepageBuilderController.php
  ├── Services/HomepageBuilderService.php
  └── Models/HomepageSection.php

database/
  └── migrations/YYYY_MM_DD_HHMMSS_create_homepage_sections_table.php

resources/views/
  ├── admin/homepage-builder/
  │   ├── index.blade.php
  │   └── partials/
  │       ├── section-item.blade.php
  │       └── modals/
  │           ├── section-config-article_carousel.blade.php
  │           ├── section-config-category_highlight.blade.php
  │           ├── section-config-newsletter.blade.php
  │           ├── section-config-image_banner.blade.php
  │           ├── section-config-text_image_split.blade.php
  │           ├── section-config-blog_preview.blade.php
  │           ├── section-config-testimonial.blade.php
  │           └── section-config-lookbook.blade.php
  └── frontend/sections/
      ├── article_carousel.blade.php
      ├── category_highlight.blade.php
      ├── newsletter.blade.php
      ├── image_banner.blade.php
      ├── text_image_split.blade.php
      ├── blog_preview.blade.php
      ├── testimonial.blade.php
      └── lookbook.blade.php

public/
  └── js/homepage-builder.js
```

### Modified Files

```
routes/web.php (add homebuilder routes)
app/Http/Controllers/Frontend/HomepageController.php (add section rendering logic)
resources/views/frontend/home.blade.php (add fallback check + section loop)
```

---

## Tasks

### PHASE 1: Database & Models (30 min)

### Task 1: Create Migration for homepage_sections Table

**Files:**

- Create: `database/migrations/YYYY_MM_DD_HHMMSS_create_homepage_sections_table.php`

**Interfaces:**

- Produces: `homepage_sections` table with columns: id, page_type, section_type, title, subtitle, config (JSON), order (int), status (bool), settings (JSON), timestamps, indexes

- [ ] **Step 1: Create migration file**

Run: `php artisan make:migration create_homepage_sections_table`

This generates `database/migrations/YYYY_MM_DD_HHMMSS_create_homepage_sections_table.php`

- [ ] **Step 2: Write migration up() method**

Replace the `up()` method in the migration with:

```php
public function up(): void
{
    Schema::create('homepage_sections', function (Blueprint $table) {
        $table->id();
        $table->enum('page_type', ['homepage', 'blog_index'])->default('homepage');
        $table->string('section_type', 50);
        $table->string('title', 255)->nullable();
        $table->text('subtitle')->nullable();
        $table->json('config')->default('{}');
        $table->integer('order')->default(0);
        $table->boolean('status')->default(true);
        $table->json('settings')->nullable();
        $table->timestamps();

        $table->index('page_type');
        $table->index('section_type');
        $table->index('order');
        $table->index('status');
    });
}
```

- [ ] **Step 3: Write migration down() method**

```php
public function down(): void
{
    Schema::dropIfExists('homepage_sections');
}
```

- [ ] **Step 4: Run migration**

Run: `php artisan migrate`

Expected output: "Migrated: database/migrations/YYYY_MM_DD_HHMMSS_create_homepage_sections_table.php"

Verify: `php artisan tinker` then `DB::table('homepage_sections')->getColumns()`

- [ ] **Step 5: Commit**

```bash
git add database/migrations/
git commit -m "feat: create homepage_sections table migration"
```

---

### Task 2: Create HomepageSection Model with SECTION_TYPES Constant

**Files:**

- Create: `app/Models/HomepageSection.php`

**Interfaces:**

- Produces:
    - Class `HomepageSection extends Model`
    - Casts: `config` (array), `settings` (array), `status` (bool), `order` (int)
    - Constant `SECTION_TYPES` array with 8 types and metadata
    - Scopes: `scopeActive()`, `scopeOrdered()`, `scopeByPage($pageType)`
    - Accessor: `getTypeLabelAttribute()`

- [ ] **Step 1: Generate model**

Run: `php artisan make:model HomepageSection`

- [ ] **Step 2: Write complete model**

Replace entire `app/Models/HomepageSection.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'config' => 'array',
        'settings' => 'array',
        'status' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = ['type_label'];

    const SECTION_TYPES = [
        'article_carousel' => [
            'label' => 'Article Carousel',
            'icon' => 'fas fa-newspaper',
            'description' => 'Featured atau latest articles dalam carousel',
            'color' => 'info',
        ],
        'category_highlight' => [
            'label' => 'Category Highlight',
            'icon' => 'fas fa-folder-open',
            'description' => 'Featured categories dengan jumlah artikel',
            'color' => 'warning',
        ],
        'newsletter' => [
            'label' => 'Newsletter',
            'icon' => 'fas fa-envelope',
            'description' => 'Form langganan email',
            'color' => 'primary',
        ],
        'image_banner' => [
            'label' => 'Image Banner',
            'icon' => 'fas fa-image',
            'description' => 'Banner gambar full-width dengan optional CTA',
            'color' => 'secondary',
        ],
        'text_image_split' => [
            'label' => 'Text + Image Split',
            'icon' => 'fas fa-columns',
            'description' => 'Layout 2 kolom: teks + gambar',
            'color' => 'success',
        ],
        'blog_preview' => [
            'label' => 'Blog Preview',
            'icon' => 'fas fa-blog',
            'description' => 'Latest blog articles dalam grid',
            'color' => 'info',
        ],
        'testimonial' => [
            'label' => 'Testimonial',
            'icon' => 'fas fa-quote-right',
            'description' => 'Reader quotes dan reviews',
            'color' => 'danger',
        ],
        'lookbook' => [
            'label' => 'Lookbook / Editorial',
            'icon' => 'fas fa-book-open',
            'description' => 'Editorial stories dan features',
            'color' => 'dark',
        ],
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function scopeByPage(Builder $query, string $pageType): Builder
    {
        return $query->where('page_type', $pageType);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::SECTION_TYPES[$this->section_type]['label']
            ?? ucfirst(str_replace('_', ' ', $this->section_type));
    }
}
```

- [ ] **Step 3: Test model**

Run: `php artisan tinker`

```php
App\Models\HomepageSection::create([
    'page_type' => 'homepage',
    'section_type' => 'article_carousel',
    'title' => 'Featured Articles',
    'config' => ['limit' => 10, 'columns' => 3],
    'order' => 1,
    'status' => true,
]);

$section = App\Models\HomepageSection::first();
echo $section->type_label; // Should print: Article Carousel
```

- [ ] **Step 4: Commit**

```bash
git add app/Models/HomepageSection.php
git commit -m "feat: create HomepageSection model with SECTION_TYPES constant"
```

---

### Task 3: Create HomepageBuilderService with CRUD & Data Resolution Methods

**Files:**

- Create: `app/Services/HomepageBuilderService.php`

**Interfaces:**

- Consumes: `HomepageSection` model
- Produces:
    - Class `HomepageBuilderService`
    - Public methods: `getAllSections($pageType)`, `getActiveSections($pageType)`, `createSection(array $data)`, `updateSection(HomepageSection $section, array $data)`, `deleteSection(HomepageSection $section)`, `toggleStatus(HomepageSection $section)`, `reorderSections(array $orders)`, `getSectionData(HomepageSection $section)`, `getSectionTypes()`
    - Private methods for each section type data resolution

- [ ] **Step 1: Create service class**

Create `app/Services/HomepageBuilderService.php`:

```php
<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\HomepageSection;
use Illuminate\Support\Collection;

class HomepageBuilderService
{
    public function getAllSections(string $pageType): Collection
    {
        return HomepageSection::byPage($pageType)->ordered()->get();
    }

    public function getActiveSections(string $pageType): Collection
    {
        return HomepageSection::byPage($pageType)->active()->ordered()->get();
    }

    public function getSectionTypes(): array
    {
        return HomepageSection::SECTION_TYPES;
    }

    public function createSection(array $data): HomepageSection
    {
        $data['order'] = HomepageSection::max('order') + 1;
        return HomepageSection::create($data);
    }

    public function updateSection(HomepageSection $section, array $data): HomepageSection
    {
        $section->update($data);
        return $section;
    }

    public function deleteSection(HomepageSection $section): bool
    {
        return $section->delete();
    }

    public function toggleStatus(HomepageSection $section): HomepageSection
    {
        $section->status = !$section->status;
        $section->save();
        return $section;
    }

    public function reorderSections(array $orders): void
    {
        foreach ($orders as $orderData) {
            HomepageSection::where('id', $orderData['id'])
                ->update(['order' => $orderData['order']]);
        }
    }

    public function getSectionData(HomepageSection $section): array
    {
        $config = $section->config ?? [];

        return match ($section->section_type) {
            'article_carousel' => $this->getArticleCarouselData($config),
            'category_highlight' => $this->getCategoryHighlightData($config),
            'blog_preview' => $this->getBlogPreviewData($config),
            'testimonial' => $this->getTestimonialData($config),
            'newsletter' => [],
            'image_banner' => [],
            'text_image_split' => [],
            'lookbook' => [],
            default => [],
        };
    }

    private function getArticleCarouselData(array $config): array
    {
        $limit = $config['limit'] ?? 10;
        $categoryFilter = $config['category_filter'] ?? null;
        $sortBy = $config['sort_by'] ?? 'latest';

        $query = Article::published();

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        if ($sortBy === 'latest') {
            $query->latest('published_at');
        }

        $articles = $query->limit($limit)->get();

        return ['articles' => $articles];
    }

    private function getCategoryHighlightData(array $config): array
    {
        $categoryIds = $config['category_ids'] ?? [];
        if (is_string($categoryIds)) {
            $categoryIds = json_decode($categoryIds, true) ?? [];
        }

        $categories = Category::whereIn('id', (array) $categoryIds)
            ->withCount('articles')
            ->get();

        return ['categories' => $categories];
    }

    private function getBlogPreviewData(array $config): array
    {
        $limit = $config['limit'] ?? 5;
        $sortBy = $config['sort_by'] ?? 'latest';

        $query = Article::published();

        if ($sortBy === 'latest') {
            $query->latest('published_at');
        }

        $articles = $query->limit($limit)->get();

        return ['articles' => $articles];
    }

    private function getTestimonialData(array $config): array
    {
        // Placeholder: return empty for now
        // Can be extended to fetch from comments table later
        return ['testimonials' => []];
    }
}
```

- [ ] **Step 2: Test service**

Run: `php artisan tinker`

```php
$service = app(App\Services\HomepageBuilderService::class);

// Test getAllSections
$sections = $service->getAllSections('homepage');
echo $sections->count(); // Should be >= 0

// Test createSection
$section = $service->createSection([
    'page_type' => 'homepage',
    'section_type' => 'article_carousel',
    'title' => 'Featured Articles',
    'config' => ['limit' => 10, 'columns' => 3],
]);
echo $section->id; // Should print new ID

// Test getSectionData
$data = $service->getSectionData($section);
echo json_encode($data); // Should show articles array
```

- [ ] **Step 3: Commit**

```bash
git add app/Services/HomepageBuilderService.php
git commit -m "feat: create HomepageBuilderService with CRUD and data resolution"
```

---

### PHASE 2: Admin Controller & Routes (45 min)

### Task 4: Create HomepageBuilderController with Route Actions

**Files:**

- Create: `app/Http/Controllers/Admin/HomepageBuilderController.php`

**Interfaces:**

- Consumes: `HomepageBuilderService`, `HomepageSection` model, `Illuminate\Http\Request`
- Produces:
    - Methods: `index($pageType)`, `store(Request $request)`, `update(Request $request, HomepageSection $section)`, `destroy(HomepageSection $section)`, `toggle(HomepageSection $section)`, `reorder(Request $request)`
    - JSON responses for admin UI

- [ ] **Step 1: Generate controller**

Run: `php artisan make:controller Admin/HomepageBuilderController`

- [ ] **Step 2: Write complete controller**

Replace `app/Http/Controllers/Admin/HomepageBuilderController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Services\HomepageBuilderService;
use Illuminate\Http\Request;

class HomepageBuilderController extends Controller
{
    public function __construct(protected HomepageBuilderService $service) {}

    public function index(string $pageType = 'homepage')
    {
        if (!in_array($pageType, ['homepage', 'blog_index'])) {
            abort(404);
        }

        $sections = $this->service->getAllSections($pageType);
        $sectionTypes = $this->service->getSectionTypes();

        return view('admin.homepage-builder.index', compact('sections', 'sectionTypes', 'pageType'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'page_type' => 'required|in:homepage,blog_index',
                'section_type' => 'required|string',
                'title' => 'nullable|string|max:255',
                'config' => 'nullable|array',
            ]);

            $section = $this->service->createSection($validated);

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Section berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, HomepageSection $section)
    {
        try {
            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'config' => 'nullable|array',
            ]);

            $section = $this->service->updateSection($section, $validated);

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Section berhasil diupdate.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(HomepageSection $section)
    {
        try {
            $this->service->deleteSection($section);

            return response()->json([
                'success' => true,
                'message' => 'Section berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function toggle(HomepageSection $section)
    {
        try {
            $section = $this->service->toggleStatus($section);

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Status berhasil diubah.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $orders = $request->validate([
                'orders' => 'required|array',
                'orders.*.id' => 'required|integer',
                'orders.*.order' => 'required|integer',
            ])['orders'];

            $this->service->reorderSections($orders);

            return response()->json([
                'success' => true,
                'message' => 'Urutan section berhasil diubah.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
```

- [ ] **Step 3: Test controller**

Run: `php artisan route:list | grep homepage-builder`

Should show your routes (added in next task)

- [ ] **Step 4: Commit**

```bash
git add app/Http/Controllers/Admin/HomepageBuilderController.php
git commit -m "feat: create HomepageBuilderController with CRUD actions"
```

---

### Task 5: Add Routes to web.php

**Files:**

- Modify: `routes/web.php`

**Interfaces:**

- Consumes: `HomepageBuilderController`
- Produces: Routes at `/admin/homepage-builder/{pageType}`, `/admin/homepage-builder` (POST/PUT/DELETE/PATCH)

- [ ] **Step 1: Locate routes section**

Open `routes/web.php` and find the admin middleware group (around line 69-104)

- [ ] **Step 2: Add homebuilder routes**

Add this block INSIDE the admin middleware group (before the closing `});`):

```php
// Homepage Builder Routes
Route::prefix('homepage-builder')->name('homepage-builder.')->group(function () {
    Route::get('/{pageType?}', [HomepageBuilderController::class, 'index'])->name('index');
    Route::post('/', [HomepageBuilderController::class, 'store'])->name('store');
    Route::put('/{section}', [HomepageBuilderController::class, 'update'])->name('update');
    Route::delete('/{section}', [HomepageBuilderController::class, 'destroy'])->name('destroy');
    Route::patch('/{section}/toggle', [HomepageBuilderController::class, 'toggle'])->name('toggle');
    Route::post('/reorder', [HomepageBuilderController::class, 'reorder'])->name('reorder');
});
```

- [ ] **Step 3: Add use statement**

At the top of `routes/web.php`, add:

```php
use App\Http\Controllers\Admin\HomepageBuilderController;
```

- [ ] **Step 4: Test routes**

Run: `php artisan route:list | grep homepage-builder`

Expected output:

```
admin.homepage-builder.index     GET|HEAD   /admin/homepage-builder/{pageType?}
admin.homepage-builder.store     POST       /admin/homepage-builder
admin.homepage-builder.update    PUT        /admin/homepage-builder/{section}
admin.homepage-builder.destroy   DELETE     /admin/homepage-builder/{section}
admin.homepage-builder.toggle    PATCH      /admin/homepage-builder/{section}/toggle
admin.homepage-builder.reorder   POST       /admin/homepage-builder/reorder
```

- [ ] **Step 5: Commit**

```bash
git add routes/web.php
git commit -m "feat: add homepage-builder routes"
```

---

### PHASE 3: Admin Views (60 min)

### Task 6: Create Admin Homepage Builder Index View

**Files:**

- Create: `resources/views/admin/homepage-builder/index.blade.php`

**Interfaces:**

- Consumes: `$sections` (Collection of HomepageSection), `$sectionTypes` (array), `$pageType` (string)
- Produces: HTML page with sortable section list, add section button, modals

- [ ] **Step 1: Create directory**

Run: `mkdir -p resources/views/admin/homepage-builder/partials/modals`

- [ ] **Step 2: Create main index view**

Create `resources/views/admin/homepage-builder/index.blade.php`:

```blade
<x-x-admin-layout-modern>
    <x-slot name="header">
        Homepage Builder - {{ $pageType === 'homepage' ? 'Homepage' : 'Blog Index' }}
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-home text-blue-500 mr-2"></i>
                Homepage Builder
            </h2>
            <div class="flex gap-2">
                <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-eye"></i> Preview
                </a>
                <button type="button" id="btnAddSection" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus"></i> Tambah Section
                </button>
            </div>
        </div>

        <!-- Page Tabs -->
        <div class="flex gap-4 border-b border-gray-200">
            <a href="{{ route('admin.homepage-builder.index', 'homepage') }}" class="px-4 py-2 {{ $pageType === 'homepage' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                Homepage
            </a>
            <a href="{{ route('admin.homepage-builder.index', 'blog_index') }}" class="px-4 py-2 {{ $pageType === 'blog_index' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                Blog Index
            </a>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            Drag & drop sections untuk mengubah urutan. Klik "Config" untuk mengatur konten section.
        </div>

        <!-- Sections List -->
        <div id="sortableSections" class="space-y-3">
            @forelse($sections as $section)
                @include('admin.homepage-builder.partials.section-item', compact('section'))
            @empty
                <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
                    <i class="fas fa-layer-group text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-gray-600 font-semibold mb-2">Belum ada section</h3>
                    <p class="text-gray-500 text-sm mb-4">Mulai dengan menambahkan section pertama.</p>
                    <button type="button" id="btnAddSectionEmpty" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i> Tambah Section Pertama
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal: Add Section -->
    <div id="addSectionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold">Tambah Section</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeAddModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">Pilih tipe section yang ingin ditambahkan:</p>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($sectionTypes as $type => $info)
                        <button type="button" class="section-type-card p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition-all" data-type="{{ $type }}">
                            <div class="text-2xl mb-2">
                                <i class="{{ $info['icon'] }} text-{{ $info['color'] }}-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">{{ $info['label'] }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $info['description'] }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        const pageType = '{{ $pageType }}';

        document.getElementById('btnAddSection')?.addEventListener('click', () => {
            document.getElementById('addSectionModal').classList.remove('hidden');
        });

        document.getElementById('btnAddSectionEmpty')?.addEventListener('click', () => {
            document.getElementById('addSectionModal').classList.remove('hidden');
        });

        function closeAddModal() {
            document.getElementById('addSectionModal').classList.add('hidden');
        }

        document.querySelectorAll('.section-type-card').forEach(card => {
            card.addEventListener('click', function() {
                const type = this.dataset.type;
                addSection(type);
                closeAddModal();
            });
        });

        function addSection(sectionType) {
            // Will implement AJAX in Task 8
            console.log('Add section:', sectionType);
        }

        // Initialize Sortable.js for drag-drop
        const sortable = new Sortable(document.getElementById('sortableSections'), {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function() {
                reorderSections();
            }
        });

        function reorderSections() {
            // Will implement AJAX in Task 8
            console.log('Reordering sections');
        }
    </script>
</x-admin-layout-modern>
```

- [ ] **Step 3: Test view**

Run: `php artisan serve` then visit `http://127.0.0.1:8000/admin/homepage-builder/homepage`

Should see layout with tabs for Homepage/Blog Index

- [ ] **Step 4: Commit**

```bash
git add resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: create homepage builder index view with section list"
```

---

### Task 7: Create Section Item Partial & Config Modals

**Files:**

- Create: `resources/views/admin/homepage-builder/partials/section-item.blade.php`
- Create: 8 config modal files in `resources/views/admin/homepage-builder/partials/modals/`

**Interfaces:**

- Consumes: `$section` (HomepageSection model)
- Produces: HTML section card with config button, delete button, status toggle

- [ ] **Step 1: Create section-item partial**

Create `resources/views/admin/homepage-builder/partials/section-item.blade.php`:

```blade
<div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow" data-section-id="{{ $section->id }}">
    <div class="flex items-start gap-4">
        <!-- Drag Handle -->
        <div class="drag-handle pt-1 cursor-move text-gray-400 hover:text-gray-600">
            <i class="fas fa-grip-vertical text-lg"></i>
        </div>

        <!-- Section Info -->
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-sm font-medium text-gray-700">
                    <i class="{{ HomepageSection::SECTION_TYPES[$section->section_type]['icon'] ?? 'fas fa-box' }}"></i>
                    {{ $section->type_label }}
                </span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $section->title ?? '(Untitled)' }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($section->subtitle ?? '', 100) }}</p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
            <button type="button" class="px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg" onclick="openConfigModal({{ $section->id }}, '{{ $section->section_type }}')">
                <i class="fas fa-cog mr-1"></i> Config
            </button>
            <button type="button" class="px-3 py-2 text-sm font-medium {{ $section->status ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100' }} rounded-lg" onclick="toggleStatus({{ $section->id }})">
                <i class="fas {{ $section->status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            </button>
            <button type="button" class="px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg" onclick="deleteSection({{ $section->id }})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Create article_carousel config modal**

Create `resources/views/admin/homepage-builder/partials/modals/section-config-article_carousel.blade.php`:

```blade
<div id="configModal_article_carousel" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 max-h-96 overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold">Configure Article Carousel</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeConfigModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="configForm_article_carousel" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="section_type" value="article_carousel">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Limit</label>
                <input type="number" name="config[limit]" value="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Columns</label>
                <select name="config[columns]" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="2">2 Columns</option>
                    <option value="3" selected>3 Columns</option>
                    <option value="4">4 Columns</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Show Fields</label>
                <div class="space-y-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="config[fields][]" value="image" checked class="rounded">
                        <span class="text-sm text-gray-700">Featured Image</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="config[fields][]" value="title" checked class="rounded">
                        <span class="text-sm text-gray-700">Title</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="config[fields][]" value="date" checked class="rounded">
                        <span class="text-sm text-gray-700">Date</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="config[fields][]" value="author" class="rounded">
                        <span class="text-sm text-gray-700">Author</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="config[fields][]" value="excerpt" class="rounded">
                        <span class="text-sm text-gray-700">Excerpt</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="config[sort_by]" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="latest" selected>Latest</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>

            <div class="flex gap-2 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save
                </button>
                <button type="button" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50" onclick="closeConfigModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
```

- [ ] **Step 3: Create remaining config modals (abbreviated)**

Create these 7 additional files with similar structure:

- `section-config-category_highlight.blade.php` - Fields: category_ids (multi-select), limit, columns, show_article_count (checkbox)
- `section-config-newsletter.blade.php` - Fields: button_text, placeholder_text, background_color (color picker)
- `section-config-image_banner.blade.php` - Fields: image_url (file upload), title, subtitle, cta_text, cta_url, height_mode, alignment
- `section-config-text_image_split.blade.php` - Fields: title, description, cta_text, cta_url, image_url, image_position (left/right)
- `section-config-blog_preview.blade.php` - Fields: limit, columns, fields (checkboxes), sort_by
- `section-config-testimonial.blade.php` - Fields: limit, background_color
- `section-config-lookbook.blade.php` - Fields: tag_filter, title, style (light/dark)

For brevity, create minimal versions of each with required fields:

```blade
<!-- section-config-category_highlight.blade.php -->
<div id="configModal_category_highlight" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold">Configure Category Highlight</h3>
            <button type="button" onclick="closeConfigModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="configForm_category_highlight" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Limit</label>
                <input type="number" name="config[limit]" value="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex gap-2 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
                <button type="button" onclick="closeConfigModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">Cancel</button>
            </div>
        </form>
    </div>
</div>
```

(Apply same pattern for remaining 6 modals with appropriate fields)

- [ ] **Step 4: Test views**

Visit `http://127.0.0.1:8000/admin/homepage-builder/homepage`, create a section and verify config modal appears.

- [ ] **Step 5: Commit**

```bash
git add resources/views/admin/homepage-builder/partials/
git commit -m "feat: create section item partial and all config modals"
```

---

### Task 8: Implement JavaScript for AJAX & Drag-Drop

**Files:**

- Create: `public/js/homepage-builder.js`
- Modify: `resources/views/admin/homepage-builder/index.blade.php` (add script include)

**Interfaces:**

- Produces: AJAX handlers for create, update, delete, toggle, reorder sections

- [ ] **Step 1: Create JavaScript file**

Create `public/js/homepage-builder.js`:

```javascript
const pageType = document.documentElement.dataset.pageType || "homepage";
let currentSectionId = null;
let currentSectionType = null;

function openConfigModal(sectionId, sectionType) {
    currentSectionId = sectionId;
    currentSectionType = sectionType;
    const modalId = `configModal_${sectionType}`;
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove("hidden");
        // Load current config
        loadSectionConfig(sectionId, sectionType);
    }
}

function closeConfigModal() {
    document
        .querySelectorAll('[id^="configModal_"]')
        .forEach((m) => m.classList.add("hidden"));
}

function loadSectionConfig(sectionId, sectionType) {
    // Will load current section config via AJAX
    console.log("Load config for section:", sectionId, sectionType);
}

function deleteSection(sectionId) {
    if (!confirm("Apakah Anda yakin ingin menghapus section ini?")) return;

    fetch(`/admin/homepage-builder/${sectionId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                document
                    .querySelector(`[data-section-id="${sectionId}"]`)
                    .remove();
                showToast("Section berhasil dihapus");
            } else {
                showToast(data.message || "Error", "error");
            }
        })
        .catch((e) => showToast("Error: " + e.message, "error"));
}

function toggleStatus(sectionId) {
    fetch(`/admin/homepage-builder/${sectionId}/toggle`, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                location.reload();
            } else {
                showToast(data.message || "Error", "error");
            }
        })
        .catch((e) => showToast("Error: " + e.message, "error"));
}

function reorderSections() {
    const items = document.querySelectorAll("[data-section-id]");
    const orders = Array.from(items).map((item, index) => ({
        id: parseInt(item.dataset.sectionId),
        order: index + 1,
    }));

    fetch("/admin/homepage-builder/reorder", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify({ orders }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                showToast("Urutan berhasil diubah");
            } else {
                showToast(data.message || "Error", "error");
            }
        })
        .catch((e) => showToast("Error: " + e.message, "error"));
}

function addSection(sectionType) {
    const modal = document.getElementById(`configModal_${sectionType}`);
    if (!modal) {
        showToast(
            "Config form not found for section type: " + sectionType,
            "error",
        );
        return;
    }

    const form = modal.querySelector(`form`);
    if (!form) return;

    const formData = new FormData(form);
    formData.append("page_type", pageType);
    formData.append("section_type", sectionType);
    formData.append("title", "Untitled Section");

    const data = Object.fromEntries(formData);

    fetch("/admin/homepage-builder", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                location.reload();
            } else {
                showToast(data.message || "Error", "error");
            }
        })
        .catch((e) => showToast("Error: " + e.message, "error"));
}

function showToast(message, type = "success") {
    const toast = document.createElement("div");
    const bgColor = type === "success" ? "bg-green-500" : "bg-red-500";
    toast.className = `fixed bottom-4 right-4 px-4 py-3 text-white rounded-lg ${bgColor} shadow-lg`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Initialize Sortable.js
document.addEventListener("DOMContentLoaded", () => {
    const sortableContainer = document.getElementById("sortableSections");
    if (sortableContainer) {
        new Sortable(sortableContainer, {
            animation: 150,
            handle: ".drag-handle",
            onEnd: reorderSections,
        });
    }

    // Form submissions for config modals
    document.querySelectorAll('[id^="configForm_"]').forEach((form) => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            if (currentSectionId) {
                updateSection(currentSectionId);
            } else {
                addSection(currentSectionType);
            }
        });
    });
});
```

- [ ] **Step 2: Add script to index view**

In `resources/views/admin/homepage-builder/index.blade.php`, add before closing `</x-admin-layout-modern>`:

```blade
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="{{ asset('js/homepage-builder.js') }}"></script>
```

Also add `data-page-type="{{ $pageType }}"` to the root div in index.blade.php:

```blade
<div data-page-type="{{ $pageType }}" class="space-y-6">
```

Then update JavaScript to read:

```javascript
const pageType =
    document.documentElement.dataset.pageType ||
    document.querySelector("[data-page-type]")?.dataset.pageType ||
    "homepage";
```

- [ ] **Step 3: Test AJAX**

Visit `http://127.0.0.1:8000/admin/homepage-builder/homepage`

Click "Tambah Section", select "Article Carousel", click Save. Section should appear in the list.

- [ ] **Step 4: Commit**

```bash
git add public/js/homepage-builder.js resources/views/admin/homepage-builder/index.blade.php
git commit -m "feat: add JavaScript for AJAX and drag-drop functionality"
```

---

### PHASE 4: Frontend Rendering (45 min)

### Task 9: Update Frontend HomepageController with Section Logic

**Files:**

- Modify: `app/Http/Controllers/Frontend/HomepageController.php`

**Interfaces:**

- Consumes: `HomepageBuilderService`
- Produces: View with `$sections`, `$sectionsData` if sections exist, else fallback to current logic

- [ ] **Step 1: Open HomepageController**

Open `app/Http/Controllers/Frontend/HomepageController.php`

- [ ] **Step 2: Inject service & update index method**

Replace the `index()` method:

```php
public function index()
{
    $service = app(\App\Services\HomepageBuilderService::class);
    $activeSections = $service->getActiveSections('homepage');

    if ($activeSections->isNotEmpty()) {
        // Render from homebuilder
        $sectionsData = $activeSections->map(function ($section) use ($service) {
            return [
                'section' => $section,
                'data' => $service->getSectionData($section),
            ];
        });

        return view('frontend.home', ['sections' => $sectionsData, 'useBuilder' => true]);
    }

    // Fallback: render current hardcoded homepage
    // ... existing logic for carousels, sidebar, etc
    return view('frontend.home'); // or your current fallback view
}
```

- [ ] **Step 3: Test controller**

Run: `php artisan serve` then visit `http://127.0.0.1:8000/`

Should render hardcoded homepage (since no sections exist yet)

- [ ] **Step 4: Commit**

```bash
git add app/Http/Controllers/Frontend/HomepageController.php
git commit -m "feat: update frontend controller with homebuilder section logic"
```

---

### Task 10: Create Frontend Section Partials (8 files)

**Files:**

- Create: 8 section partials in `resources/views/frontend/sections/`

**Interfaces:**

- Consumes: `$section` (HomepageSection model), `$data` (array with section-specific content)
- Produces: Rendered HTML for each section type

- [ ] **Step 1: Create article_carousel partial**

Create `resources/views/frontend/sections/article_carousel.blade.php`:

```blade
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        @if($section->title)
            <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ $section->title }}</h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-{{ $section->config['columns'] ?? 3 }} gap-6">
            @forelse($data['articles'] ?? [] as $article)
                <article class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow">
                    @if(in_array('image', $section->config['fields'] ?? []))
                        <div class="aspect-video bg-gray-200 overflow-hidden">
                            @if($article->featured_image)
                                <img src="/storage/{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400"></div>
                            @endif
                        </div>
                    @endif

                    <div class="p-4">
                        @if(in_array('category', $section->config['fields'] ?? []))
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded mb-2">
                                {{ $article->category->name ?? 'Uncategorized' }}
                            </span>
                        @endif

                        @if(in_array('title', $section->config['fields'] ?? []))
                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                <a href="{{ route('blog.show', $article) }}" class="hover:text-blue-600">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        @endif

                        @if(in_array('excerpt', $section->config['fields'] ?? []))
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $article->excerpt }}
                            </p>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            @if(in_array('date', $section->config['fields'] ?? []))
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar"></i>
                                    {{ $article->published_at?->format('d M Y') }}
                                </span>
                            @endif
                            @if(in_array('author', $section->config['fields'] ?? []))
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-user"></i>
                                    {{ $article->user->name ?? 'Author' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    No articles available
                </div>
            @endforelse
        </div>
    </div>
</section>
```

- [ ] **Step 2: Create category_highlight partial**

Create `resources/views/frontend/sections/category_highlight.blade.php`:

```blade
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($section->title)
            <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ $section->title }}</h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($data['categories'] ?? [] as $category)
                <a href="{{ route('blog.category', $category) }}" class="bg-white rounded-lg p-6 shadow hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
                            @if($section->config['show_article_count'] ?? false)
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $category->articles_count }} artikel
                                </p>
                            @endif
                        </div>
                        <i class="fas fa-arrow-right text-blue-600 text-2xl"></i>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    No categories available
                </div>
            @endforelse
        </div>
    </div>
</section>
```

- [ ] **Step 3: Create remaining 6 partials (abbreviated)**

Create these with minimal content:

**newsletter.blade.php:**

```blade
<section class="py-12" style="background-color: {{ $section->config['background_color'] ?? '#f3f4f6' }};">
    <div class="container mx-auto px-4 max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">{{ $section->title ?? 'Subscribe' }}</h2>
        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex gap-2">
            @csrf
            <input type="email" name="email" placeholder="{{ $section->config['placeholder_text'] ?? 'Enter your email' }}" required class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                {{ $section->config['button_text'] ?? 'Subscribe' }}
            </button>
        </form>
    </div>
</section>
```

**image_banner.blade.php:**

```blade
<section class="py-0">
    <div class="relative" style="height: {{ $section->config['height_mode'] === 'full' ? '500px' : '300px' }}">
        @if($section->config['image_url'] ?? false)
            <img src="{{ $section->config['image_url'] }}" alt="" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400"></div>
        @endif
        @if($section->config['cta_url'] ?? false)
            <a href="{{ $section->config['cta_url'] }}" class="absolute inset-0 opacity-0"></a>
        @endif
    </div>
</section>
```

**text_image_split.blade.php:**

```blade
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="order-2 md:order-{{ ($section->config['image_position'] ?? 'right') === 'left' ? '1' : '2' }}">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                <p class="text-gray-600 mb-6">{{ $section->subtitle }}</p>
                @if($section->config['cta_url'] ?? false)
                    <a href="{{ $section->config['cta_url'] }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        {{ $section->config['cta_text'] ?? 'Learn More' }}
                    </a>
                @endif
            </div>
            <div class="order-1 md:order-{{ ($section->config['image_position'] ?? 'right') === 'left' ? '2' : '1' }}">
                @if($section->config['image_url'] ?? false)
                    <img src="{{ $section->config['image_url'] }}" alt="" class="rounded-lg">
                @else
                    <div class="aspect-square bg-gray-300 rounded-lg"></div>
                @endif
            </div>
        </div>
    </div>
</section>
```

**blog_preview.blade.php:**

```blade
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($section->title)
            <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ $section->title }}</h2>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-{{ $section->config['columns'] ?? 2 }} gap-6">
            @forelse($data['articles'] ?? [] as $article)
                @include('frontend.sections.article_carousel', ['section' => $section, 'article' => $article])
            @empty
                <p class="text-gray-500">No articles available</p>
            @endforelse
        </div>
    </div>
</section>
```

**testimonial.blade.php:**

```blade
<section class="py-12" style="background-color: {{ $section->config['background_color'] ?? '#f3f4f6' }};">
    <div class="container mx-auto px-4">
        @if($section->title)
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">{{ $section->title }}</h2>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($data['testimonials'] ?? [] as $testimonial)
                <div class="bg-white rounded-lg p-6 shadow">
                    <p class="text-gray-700 mb-4">{{ $testimonial->comment ?? '...' }}</p>
                    <p class="font-semibold text-gray-900">{{ $testimonial->author ?? 'Reader' }}</p>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No testimonials available</p>
            @endforelse
        </div>
    </div>
</section>
```

**lookbook.blade.php:**

```blade
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="bg-cover bg-center rounded-lg p-12" style="background-image: url('{{ $section->config['image_url'] ?? '' }}'); color: {{ ($section->config['style'] ?? 'light') === 'dark' ? 'white' : 'black' }};">
            @if($section->title)
                <h2 class="text-4xl font-bold mb-4">{{ $section->title }}</h2>
            @endif
            @if($section->config['cta_url'] ?? false)
                <a href="{{ $section->config['cta_url'] }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ $section->config['cta_text'] ?? 'Explore' }}
                </a>
            @endif
        </div>
    </div>
</section>
```

- [ ] **Step 4: Test partials**

Visit admin, create an "Article Carousel" section, save it. Visit homepage — should see articles rendered.

- [ ] **Step 5: Commit all partials**

```bash
git add resources/views/frontend/sections/
git commit -m "feat: create all 8 frontend section partials"
```

---

### Task 11: Update Frontend Home View with Fallback Logic

**Files:**

- Modify: `resources/views/frontend/home.blade.php`

**Interfaces:**

- Consumes: `$useBuilder` (bool), `$sections` (array of sections with data)
- Produces: Homebuilder sections loop OR hardcoded layout

- [ ] **Step 1: Open home.blade.php**

Open `resources/views/frontend/home.blade.php`

- [ ] **Step 2: Add conditional rendering**

Add this at the top of the page content (after header):

```blade
@if($useBuilder ?? false)
    <!-- Render homebuilder sections -->
    @foreach($sections ?? [] as $item)
        @include("frontend.sections.{$item['section']->section_type}", [
            'section' => $item['section'],
            'data' => $item['data'],
        ])
    @endforeach
@else
    <!-- Fallback: render hardcoded homepage -->
    <!-- ... existing carousel, sidebar, articles code ... -->
    @include('frontend.partials.hero-carousel')
    @include('frontend.partials.sidebar')
    <!-- etc -->
@endif
```

- [ ] **Step 3: Test fallback**

Visit `http://127.0.0.1:8000/` — should show existing hardcoded homepage (since no sections created yet)

- [ ] **Step 4: Commit**

```bash
git add resources/views/frontend/home.blade.php
git commit -m "feat: add fallback logic to home view for homebuilder sections"
```

---

### PHASE 5: Testing & Final Touches (15 min)

### Task 12: Manual Testing Checklist

- [ ] **Admin CRUD:**
    - [ ] Visit `/admin/homepage-builder/homepage` — no errors
    - [ ] Click "Tambah Section" — modal opens
    - [ ] Select "Article Carousel" — form loads
    - [ ] Fill config (limit 5, 3 columns) — click Save
    - [ ] Section appears in list
    - [ ] Click "Config" on section — modal shows saved config
    - [ ] Update limit to 10 — click Save
    - [ ] List updates without page reload
    - [ ] Click delete button — section removed after confirmation

- [ ] **Drag-Drop Reordering:**
    - [ ] Create 3 sections
    - [ ] Drag section to different position
    - [ ] Refresh page — order persists

- [ ] **Frontend Rendering:**
    - [ ] Visit homepage `/` — should render Article Carousel section
    - [ ] Click on article title — navigates to article detail
    - [ ] Visit blog `/blog` — should render blog sections (if any created)

- [ ] **Fallback Logic:**
    - [ ] Delete all sections via admin
    - [ ] Visit homepage — should render hardcoded layout (not error)

- [ ] **Blog Index Tab:**
    - [ ] Visit `/admin/homepage-builder/blog_index`
    - [ ] Create sections for blog_index page_type
    - [ ] Verify sections don't appear on homepage (different page_type)

### Task 13: Commit and Create CHANGELOG Entry

- [ ] **Step 1: Final git status**

Run: `git status`

All changes should be staged.

- [ ] **Step 2: Final commit (if anything left)**

```bash
git add .
git commit -m "feat: complete homebuilder implementation with frontend rendering and testing"
```

- [ ] **Step 3: View full commit log**

Run: `git log --oneline | head -15`

Should show ~10 commits from homebuilder implementation.

---

## Success Criteria ✅

- [ ] Homepage builder accessible at `/admin/homepage-builder/homepage` and `/blog_index`
- [ ] Admin can create/edit/delete/reorder sections via drag-drop
- [ ] 8 section types fully functional with proper config forms
- [ ] Frontend renders sections dynamically on / and /blog
- [ ] Fallback to hardcoded layout if no sections configured
- [ ] No breaking changes to existing homepage
- [ ] All AJAX operations work without page reloads
- [ ] Mobile-responsive admin UI and frontend rendering
- [ ] Database migrations applied successfully
- [ ] All code committed to git with clear commit messages

---

## Execution Handoff

**Plan complete and saved to `docs/superpowers/plans/2026-07-16-homebuilder-implementation.md`.**

Two execution options:

**Option 1: Subagent-Driven (Recommended)** 🤖

- I dispatch a fresh subagent per task
- Review between tasks
- Fast iteration with checkpoints

**Option 2: Inline Execution** ⚙️

- Execute tasks in this session
- Batch execution with checkpoints for review
- Direct feedback loop

**Which approach would you like?** 🚀
