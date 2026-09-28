<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $relations = [], array $columns = ['*']): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model->query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get($columns);
    }

    public function find(int $id, array $relations = [])
    {
        $query = $this->model->query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id);
    }

    public function findBy(string $column, mixed $value, array $relations = [])
    {
        $query = $this->model->query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->where($column, $value)->first();
    }

    public function paginate(int $perPage = 15, array $relations = [], array $columns = ['*'])
    {
        $query = $this->model->query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return null;
        }

        $model->update($data);

        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->model->find($id);

        if (!$model) {
            return false;
        }

        return $model->delete();
    }

    public function forceDelete(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);

        if (!$model) {
            return false;
        }

        return $model->forceDelete();
    }

    public function restore(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);

        if (!$model || !$model->trashed()) {
            return false;
        }

        return $model->restore();
    }

    public function exists(string $column, mixed $value): bool
    {
        return $this->model->where($column, $value)->exists();
    }

    public function count(array $filters = []): int
    {
        $query = $this->model->query();

        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        return $query->count();
    }

    public function where(string $column, mixed $operator = null, mixed $value = null, array $relations = [])
    {
        $query = $this->model->query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        if ($value === null) {
            $query->where($column, $operator);
        } else {
            $query->where($column, $operator, $value);
        }

        return $query;
    }

    public function filter(array $filters = [], array $relations = [])
    {
        $query = $this->model->query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($filters as $column => $value) {
            if ($value !== null && $value !== '') {
                if (is_array($value)) {
                    $query->whereIn($column, $value);
                } else {
                    $query->where($column, $value);
                }
            }
        }

        return $query;
    }
}
