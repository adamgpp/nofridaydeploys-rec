<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyUpdate;

use App\Domain\Feature\CompanyUpdate\DTO\Company as CompanyDto;

interface CompanyUpdateInterface
{
    public function updateCompany(CompanyDto $companyDto): void;
}
