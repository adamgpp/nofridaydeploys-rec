<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Exception;

final class ValueValidationException extends \DomainException
{
    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
