<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyCreation;

use App\Domain\Entity\Company;
use App\Domain\Feature\CompanyCreation\DTO\Company as CompanyDto;
use App\Domain\Feature\CompanyCreation\Validation\CompanyCreationValidationInterface;
use App\Domain\Repository\CompanyRepositoryInterface;

final readonly class CompanyCreationService implements CompanyCreationInterface
{
    public function __construct(
        private CompanyCreationValidationInterface $companyCreationValidation,
        private CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function createCompany(CompanyDto $company): void
    {
        $this->companyCreationValidation->assertCompanyCanBeCreated($company->id, $company->taxId);

        $this->companyRepository->add(Company::createFromDto($company));
        $this->companyRepository->confirm();
    }
}
