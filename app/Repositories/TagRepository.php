<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository extends BaseRepository
{
    public function __construct(Tag $tag)
    {
        parent::__construct($tag);
    }

    /**
     * Get tags with article count
     */
    public function withCount()
    {
        return $this->model->withCount('articles')
                           ->orderBy('name')
                           ->get();
    }

    /**
     * Get popular tags
     */
    public function getPopular(int $limit = 20)
    {
        return $this->model->withCount('articles')
                           ->orderBy('articles_count', 'desc')
                           ->limit($limit)
                           ->get();
    }

    /**
     * Find tag by slug
     */
    public function findBySlug(string $slug, array $relations = [])
    {
        $query = $this->model->where('slug', $slug);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }
}
