<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Get active users
     */
    public function getActive(array $relations = [])
    {
        $query = $this->model->where('is_active', true);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get users with role
     */
    public function getUsersWithRole(string $role, array $relations = [])
    {
        $query = $this->model->role($role);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get users with specific permission
     */
    public function getUsersWithPermission(string $permission, array $relations = [])
    {
        $query = $this->model->permission($permission);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email, array $relations = [])
    {
        $query = $this->model->where('email', $email);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Get user article count
     */
    public function getWithArticleCount(array $relations = [])
    {
        $query = $this->model->withCount('articles');

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('name')->get();
    }
}
