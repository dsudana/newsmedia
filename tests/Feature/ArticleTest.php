<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test can view article detail page
     */
    public function test_can_view_article_detail(): void
    {
        $article = Article::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->get(route('blog.show', $article->slug));

        $response->assertSuccessful();
    }

    /**
     * Test cannot view unpublished article
     */
    public function test_cannot_view_unpublished_article(): void
    {
        $article = Article::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->get(route('blog.show', $article->slug));

        $response->assertNotFound();
    }

    /**
     * Test article index page loads
     */
    public function test_article_index_loads(): void
    {
        Article::factory()->count(5)->create(['status' => 'published']);

        $response = $this->get(route('blog.index'));

        $response->assertSuccessful();
    }

    /**
     * Test can filter articles by category
     */
    public function test_can_filter_by_category(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(3)->create([
            'status' => 'published',
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('blog.category', $category->slug));

        $response->assertSuccessful();
    }

    /**
     * Test can search articles
     */
    public function test_can_search_articles(): void
    {
        Article::factory()->create([
            'title' => 'Unique Article Title',
            'status' => 'published',
        ]);

        $response = $this->get(route('blog.search', ['q' => 'Unique']));

        $response->assertSuccessful();
    }

    /**
     * Test article slug generation
     */
    public function test_article_slug_generation(): void
    {
        $article = Article::factory()->create([
            'title' => 'This Is A Test Article',
            'slug' => null,
        ]);

        $this->assertNotNull($article->slug);
        $this->assertEquals('this-is-a-test-article', $article->slug);
    }

    /**
     * Test article auto-increments word count
     */
    public function test_article_calculates_word_count(): void
    {
        $article = Article::factory()->create([
            'content' => 'This is a test content with several words in it.',
        ]);

        // After creation, word_count should be calculated
        $this->assertNotNull($article->word_count);
        $this->assertGreaterThan(0, $article->word_count);
    }

    /**
     * Test article soft delete
     */
    public function test_article_soft_delete(): void
    {
        $article = Article::factory()->create();
        $articleId = $article->id;

        $article->delete();

        // Should not appear in regular queries
        $this->assertNull(Article::find($articleId));

        // Should appear in withTrashed queries
        $this->assertNotNull(Article::withTrashed()->find($articleId));
    }

    /**
     * Test rate limiting on article view
     */
    public function test_article_view_has_rate_limiting(): void
    {
        $article = Article::factory()->create(['status' => 'published']);

        // Make multiple requests
        for ($i = 0; $i < 5; $i++) {
            $response = $this->get(route('blog.show', $article->slug));
            $response->assertSuccessful();
        }

        // Check rate limit header exists
        $response = $this->get(route('blog.show', $article->slug));
        $this->assertTrue(
            $response->headers->has('RateLimit-Limit') ||
            $response->headers->has('X-RateLimit-Limit')
        );
    }

    /**
     * Test home page loads successfully
     */
    public function test_home_page_loads(): void
    {
        Article::factory()->count(20)->create(['status' => 'published']);

        $response = $this->get(route('home'));

        $response->assertSuccessful();
    }
}
