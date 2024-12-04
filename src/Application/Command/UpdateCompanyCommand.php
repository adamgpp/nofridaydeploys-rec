<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Feature\CompanyUpdate\DTO\Company as CompanyDto;

/**
 * @see UpdateCompanyHandler
 */
final readonly class UpdateCompanyCommand implements CommandInterface
{
    public function __construct(public CompanyDto $company)
    {
    }
}
