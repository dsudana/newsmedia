<?php

namespace App\Exceptions;

use Exception;

class ArticleException extends Exception
{
    public static function notFound(int $id)
    {
        return new static(
            message: "Artikel dengan ID {$id} tidak ditemukan",
            code: 404
        );
    }

    public static function alreadyPublished(int $id)
    {
        return new static(
            message: "Artikel dengan ID {$id} sudah dipublikasi",
            code: 409
        );
    }

    public static function cannotDelete(int $id, string $reason = '')
    {
        $message = "Artikel dengan ID {$id} tidak bisa dihapus";
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new static(message: $message, code: 403);
    }

    public static function invalidStatus(string $status)
    {
        return new static(
            message: "Status artikel '{$status}' tidak valid. Gunakan: draft, published, scheduled, archived",
            code: 422
        );
    }

    public static function unauthorizedAccess(int $articleId, int $userId)
    {
        return new static(
            message: "User {$userId} tidak memiliki akses ke artikel {$articleId}",
            code: 403
        );
    }

    public static function invalidMetadata(string $reason = '')
    {
        $message = "Metadata artikel tidak valid";
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new static(message: $message, code: 422);
    }
}
