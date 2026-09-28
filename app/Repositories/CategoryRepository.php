<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    /**
     * Get active categories ordered
     */
    public function getActive(array $relations = [])
    {
        $query = $this->model->where('is_active', true);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('order')->orderBy('name')->get();
    }

    /**
     * Get categories with article count
     */
    public function withCount(array $relations = [])
    {
        $query = $this->model->withCount('articles');

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('order')->orderBy('name')->get();
    }

    /**
     * Get parent categories (for dropdown)
     */
    public function getParents(array $relations = [])
    {
        $query = $this->model->whereNull('parent_id');

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('order')->orderBy('name')->get();
    }

    /**
     * Get categories with children
     */
    public function getWithChildren(array $relations = [])
    {
        $relations[] = 'children';

        return $this->getActive($relations);
    }
}
