<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = 'Anda tidak memiliki izin untuk melakukan aksi ini')
    {
        parent::__construct(message: $message, code: 403);
    }

    public static function missingPermission(string $permission): static
    {
        return new static("Anda tidak memiliki permission '{$permission}'");
    }

    public static function missingRole(string $role): static
    {
        return new static("Anda harus memiliki role '{$role}' untuk aksi ini");
    }

    public static function resourceOwnerOnly(string $resource): static
    {
        return new static("Hanya pemilik {$resource} yang bisa melakukan aksi ini");
    }
}
