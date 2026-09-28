<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * Get all records with optional relations
     */
    public function all(array $relations = [], array $columns = ['*']): \Illuminate\Database\Eloquent\Collection;

    /**
     * Find record by ID
     */
    public function find(int $id, array $relations = []);

    /**
     * Find by column value
     */
    public function findBy(string $column, mixed $value, array $relations = []);

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $relations = [], array $columns = ['*']);

    /**
     * Create new record
     */
    public function create(array $data);

    /**
     * Update record
     */
    public function update(int $id, array $data);

    /**
     * Delete record
     */
    public function delete(int $id): bool;

    /**
     * Force delete (hard delete)
     */
    public function forceDelete(int $id): bool;

    /**
     * Restore soft-deleted record
     */
    public function restore(int $id): bool;

    /**
     * Check if record exists
     */
    public function exists(string $column, mixed $value): bool;

    /**
     * Count records
     */
    public function count(array $filters = []): int;

    /**
     * Get records filtered by column
     */
    public function where(string $column, mixed $operator = null, mixed $value = null, array $relations = []);

    /**
     * Get records with advanced filtering
     */
    public function filter(array $filters = [], array $relations = []);
}
