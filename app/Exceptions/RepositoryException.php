<?php

namespace App\Exceptions;

use Exception;

class RepositoryException extends Exception
{
    public static function modelNotFound(string $model, mixed $identifier)
    {
        return new static(
            message: "{$model} dengan identifier '{$identifier}' tidak ditemukan",
            code: 404
        );
    }

    public static function operationFailed(string $operation, string $model, string $reason = '')
    {
        $message = "Operasi {$operation} pada {$model} gagal";
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new static(message: $message, code: 500);
    }

    public static function invalidData(string $message)
    {
        return new static(message: $message, code: 422);
    }
}
