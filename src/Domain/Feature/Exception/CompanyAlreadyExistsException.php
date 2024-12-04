<?php

declare(strict_types=1);

namespace App\Domain\Feature\Exception;

final class CompanyAlreadyExistsException extends \DomainException
{
    public static function byId(): self
    {
        return new self('Company by ID already exists.');
    }

    public static function byTaxId(): self
    {
        return new self('Company by tax ID already exists.');
    }
}
