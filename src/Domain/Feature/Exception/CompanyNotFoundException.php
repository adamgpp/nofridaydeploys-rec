<?php

declare(strict_types=1);

namespace App\Domain\Feature\Exception;

final class CompanyNotFoundException extends \DomainException
{
    public static function create(): self
    {
        return new self('Company not found.');
    }
}
