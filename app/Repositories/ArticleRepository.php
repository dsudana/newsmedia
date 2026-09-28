<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleMeta;
use App\Models\ArticleFaq;
use App\Helpers\SummernoteHelper;

class ArticleRepository extends BaseRepository
{
    public function __construct(Article $article)
    {
        parent::__construct($article);
    }

    /**
     * Get articles with search, status, and category filters
     */
    public function getFiltered(array $filters = [], int $perPage = 15)
    {
        $query = $this->model->with(['category', 'user', 'meta']);

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Category filter
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        return $query->latest()->paginate($perPage)->appends($filters);
    }

    /**
     * Create article with metadata, tags, and keywords
     */
    public function createWithRelations(array $articleData, array $metaData = [], array $tagIds = [], array $keywordIds = [])
    {
        // Process images in content
        if (!empty($articleData['content'])) {
            $articleData['content'] = SummernoteHelper::extractAndSaveImages($articleData['content']);

            // Extract first image for featured_image if not set
            if (empty($articleData['featured_image'])) {
                $firstImage = SummernoteHelper::getFirstImage($articleData['content']);
                if ($firstImage) {
                    $articleData['featured_image'] = $firstImage;
                }
            }
        }

        $article = $this->create($articleData);

        if (!empty($metaData)) {
            ArticleMeta::create(array_merge(['article_id' => $article->id], $metaData));
        }

        if (!empty($tagIds)) {
            $article->tags()->sync($tagIds);
        }

        if (!empty($keywordIds)) {
            $article->keywords()->sync($keywordIds);
        }

        return $article->load('meta', 'tags', 'keywords');
    }

    /**
     * Update article with metadata, tags, keywords, and FAQs
     */
    public function updateWithRelations(int $id, array $articleData, array $metaData = [], array $tagIds = [], array $keywordIds = [], array $faqs = [])
    {
        $article = $this->model->find($id);
        if (!$article) {
            return null;
        }

        // Delete old images if content is being updated
        if (!empty($articleData['content']) && $article->content !== $articleData['content']) {
            SummernoteHelper::deleteImages($article->content);
        }

        // Process images in new content
        if (!empty($articleData['content'])) {
            $articleData['content'] = SummernoteHelper::extractAndSaveImages($articleData['content']);

            // Extract first image for featured_image if not manually set
            if (empty($articleData['featured_image'])) {
                $firstImage = SummernoteHelper::getFirstImage($articleData['content']);
                if ($firstImage) {
                    $articleData['featured_image'] = $firstImage;
                }
            }
        }

        $article = $this->update($id, $articleData);

        if (!$article) {
            return null;
        }

        // Update or create metadata
        if (!empty($metaData)) {
            if ($article->meta) {
                $article->meta->update($metaData);
            } else {
                ArticleMeta::create(array_merge(['article_id' => $article->id], $metaData));
            }
        }

        // Sync tags
        if (!empty($tagIds)) {
            $article->tags()->sync($tagIds);
        } else {
            $article->tags()->detach();
        }

        // Sync keywords
        if (!empty($keywordIds)) {
            $article->keywords()->sync($keywordIds);
        } else {
            $article->keywords()->detach();
        }

        // Update FAQs
        if (!empty($faqs)) {
            $article->faqs()->delete();
            foreach ($faqs as $index => $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    ArticleFaq::create([
                        'article_id' => $article->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'order' => $index,
                    ]);
                }
            }
        }

        return $article->load('meta', 'tags', 'keywords', 'faqs');
    }

    /**
     * Get published articles
     */
    public function getPublished(int $perPage = 10, array $relations = [])
    {
        $query = $this->model->where('status', 'published');

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    /**
     * Get articles by category
     */
    public function getByCategory(int $categoryId, int $perPage = 10, array $relations = [])
    {
        $query = $this->model->where('category_id', $categoryId)
                             ->where('status', 'published');

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    /**
     * Bulk update articles
     */
    public function bulkUpdate(array $ids, array $data): int
    {
        return $this->model->whereIn('id', $ids)->update($data);
    }

    /**
     * Bulk delete articles
     */
    public function bulkDelete(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Get popular articles by view count
     */
    public function getPopular(int $limit = 10, array $relations = [])
    {
        $query = $this->model->where('status', 'published')
                             ->orderBy('views_count', 'desc');

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Search articles with advanced filters
     */
    public function search(string $term, array $filters = [], int $perPage = 15)
    {
        $query = $this->model->with(['category', 'user', 'meta']);

        // Full-text search
        $query->where(function($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%")
              ->orWhere('excerpt', 'like', "%{$term}%");
        });

        // Apply additional filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest()->paginate($perPage);
    }
}
