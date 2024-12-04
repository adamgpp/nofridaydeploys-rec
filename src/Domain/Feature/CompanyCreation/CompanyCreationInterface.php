<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyCreation;

use App\Domain\Feature\CompanyCreation\DTO\Company;

interface CompanyCreationInterface
{
    public function createCompany(Company $company): void;
}
