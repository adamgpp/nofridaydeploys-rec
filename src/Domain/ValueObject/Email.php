<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\ValueObject\Exception\ValueValidationException;

final readonly class Email
{
    public function __construct(public string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw ValueValidationException::withMessage('Email value should be a valid email address.');
        }
    }
}
