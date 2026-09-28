<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportExportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test admin user
        $this->admin = User::factory()->create();
    }

    /**
     * Test export articles to CSV
     */
    public function test_can_export_articles_to_csv(): void
    {
        // Create test articles
        Article::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.import-export.export'));

        $response->assertSuccessful();
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        $response->assertHeader('Content-Disposition', fn($value) => str_contains($value, 'articles_'));
    }

    /**
     * Test export returns empty message when no articles
     */
    public function test_export_returns_error_when_no_articles(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.import-export.export'));

        $response->assertRedirect(route('admin.import-export.index'));
        $response->assertSessionHas('error', 'Tidak ada artikel untuk diexport');
    }

    /**
     * Test can download import template
     */
    public function test_can_download_template(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.import-export.template'));

        $response->assertSuccessful();
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        $response->assertHeader('Content-Disposition', fn($value) => str_contains($value, 'template'));
    }

    /**
     * Test import requires file
     */
    public function test_import_requires_file(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), []);

        $response->assertSessionHasErrors('file');
    }

    /**
     * Test import validates file type
     */
    public function test_import_validates_file_type(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), [
                'file' => \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 100),
            ]);

        $response->assertSessionHasErrors('file');
    }

    /**
     * Test successful import of CSV
     */
    public function test_can_import_valid_csv(): void
    {
        // Create a valid CSV file
        $csvContent = "Title,Slug,Content,Excerpt,Category,Author,Featured Image,Published At,Status\n";
        $csvContent .= "Test Article 1,test-article-1,Content here,Excerpt here,News,Admin User,,2026-07-15 10:00:00,published\n";
        $csvContent .= "Test Article 2,test-article-2,More content,Another excerpt,Tech,Admin User,,2026-07-15 11:00:00,published\n";

        $file = \Illuminate\Http\UploadedFile::fake()
            ->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), [
                'file' => $file,
                'skip_duplicates' => true,
            ]);

        $response->assertRedirect(route('admin.import-export.index'));
        $response->assertSessionHas('success');

        // Verify articles were created
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article 1',
            'slug' => 'test-article-1',
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article 2',
            'slug' => 'test-article-2',
        ]);
    }

    /**
     * Test import rejects invalid slug format
     */
    public function test_import_rejects_invalid_slug(): void
    {
        $csvContent = "Title,Slug,Content,Excerpt,Category,Author,Featured Image,Published At,Status\n";
        $csvContent .= "Test Article,INVALID SLUG,Content here,Excerpt,News,Admin,,2026-07-15 10:00:00,published\n";

        $file = \Illuminate\Http\UploadedFile::fake()
            ->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.import-export.index'));
        $response->assertSessionHasErrors();

        // Article should not be created
        $this->assertDatabaseMissing('articles', ['title' => 'Test Article']);
    }

    /**
     * Test import rejects invalid featured image URL
     */
    public function test_import_rejects_invalid_image_url(): void
    {
        $csvContent = "Title,Slug,Content,Excerpt,Category,Author,Featured Image,Published At,Status\n";
        $csvContent .= "Test Article,test-article,Content,Excerpt,News,Admin,not-a-url,2026-07-15 10:00:00,published\n";

        $file = \Illuminate\Http\UploadedFile::fake()
            ->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.import-export.index'));
        $response->assertSessionHasErrors();
    }

    /**
     * Test import skips duplicates when requested
     */
    public function test_import_skips_duplicates(): void
    {
        // Create existing article
        Article::factory()->create(['slug' => 'existing-article']);

        $csvContent = "Title,Slug,Content,Excerpt,Category,Author,Featured Image,Published At,Status\n";
        $csvContent .= "Existing Article,existing-article,New content,New excerpt,News,Admin,,2026-07-15 10:00:00,published\n";

        $file = \Illuminate\Http\UploadedFile::fake()
            ->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), [
                'file' => $file,
                'skip_duplicates' => true,
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('articles', ['title' => 'Existing Article']);
    }

    /**
     * Test import validates required columns
     */
    public function test_import_validates_required_columns(): void
    {
        // CSV missing required columns
        $csvContent = "Name,Description\n";
        $csvContent .= "Test,Some description\n";

        $file = \Illuminate\Http\UploadedFile::fake()
            ->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.import-export.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.import-export.index'));
        $response->assertSessionHas('error', fn($msg) => str_contains($msg, 'Kolom yang diperlukan'));
    }

    /**
     * Test import requires authentication
     */
    public function test_import_requires_authentication(): void
    {
        $response = $this->get(route('admin.import-export.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test export requires authentication
     */
    public function test_export_requires_authentication(): void
    {
        $response = $this->get(route('admin.import-export.export'));

        $response->assertRedirect(route('login'));
    }
}
