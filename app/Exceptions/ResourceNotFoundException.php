<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function __construct(string $resource, mixed $identifier)
    {
        $message = "{$resource} dengan identifier '{$identifier}' tidak ditemukan";
        parent::__construct(message: $message, code: 404);
    }

    public static function create(string $resource, mixed $identifier): static
    {
        return new static($resource, $identifier);
    }
}
