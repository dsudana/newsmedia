<?php

namespace App\Exceptions;

use Exception;

class BusinessLogicException extends Exception
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct(message: $message, code: $code);
    }

    public static function conflict(string $message): static
    {
        return new static($message, code: 409);
    }

    public static function invalidOperation(string $operation, string $reason): static
    {
        return new static("Operasi '{$operation}' tidak bisa dilakukan: {$reason}", code: 400);
    }

    public static function stateError(string $currentState, string $expectedState): static
    {
        return new static(
            "Status saat ini '{$currentState}' tidak memungkinkan operasi ini (expected: '{$expectedState}')",
            code: 409
        );
    }
}
