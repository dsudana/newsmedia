<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected array $errors = [];

    public function __construct(string $message, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct(message: $message, code: 422);
    }

    public static function withErrors(array $errors): static
    {
        $message = 'Validasi data gagal';
        return new static($message, $errors);
    }

    public static function singleField(string $field, string $rule): static
    {
        return new static(
            message: "Field '{$field}' tidak valid: {$rule}",
            errors: [$field => $rule]
        );
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(): ?string
    {
        $errors = array_values($this->errors);
        return $errors[0] ?? null;
    }
}
