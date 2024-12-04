<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Feature\CompanyCreation\DTO\Company as CompanyDto;

/**
 * @see CreateCompanyHandler
 */
final readonly class CreateCompanyCommand implements CommandInterface
{
    public function __construct(public CompanyDto $company)
    {
    }
}
