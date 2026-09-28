<?php

namespace App\Exceptions;

use Exception;

class CategoryException extends Exception
{
    public static function notFound(int $id)
    {
        return new static(
            message: "Kategori dengan ID {$id} tidak ditemukan",
            code: 404
        );
    }

    public static function duplicateSlug(string $slug)
    {
        return new static(
            message: "Slug '{$slug}' sudah digunakan oleh kategori lain",
            code: 409
        );
    }

    public static function cannotDeleteWithArticles(int $id, int $articleCount)
    {
        return new static(
            message: "Kategori dengan ID {$id} tidak bisa dihapus karena memiliki {$articleCount} artikel",
            code: 409
        );
    }

    public static function invalidParent(int $parentId)
    {
        return new static(
            message: "Kategori parent dengan ID {$parentId} tidak ditemukan",
            code: 404
        );
    }

    public static function circularReference(int $categoryId, int $parentId)
    {
        return new static(
            message: "Tidak bisa mengatur kategori {$parentId} sebagai parent dari {$categoryId} (circular reference)",
            code: 422
        );
    }
}
