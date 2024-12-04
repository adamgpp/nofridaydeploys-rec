<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\ValueObject\Exception\ValueValidationException;

final readonly class BaseString
{
    private const int MIN_LENGTH = 1;
    private const int MAX_LENGTH = 255;

    public function __construct(public string $value)
    {
        if (strlen($this->value) < self::MIN_LENGTH) {
            throw ValueValidationException::withMessage(sprintf('BaseString length cannot be lower than %s character.', self::MIN_LENGTH));
        }

        if (strlen($this->value) > self::MAX_LENGTH) {
            throw ValueValidationException::withMessage(sprintf('BaseString length cannot be greater than %s characters.', self::MAX_LENGTH));
        }
    }
}
