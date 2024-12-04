<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyUpdate;

use App\Domain\Country;
use App\Domain\Entity\Company;
use App\Domain\Feature\CompanyUpdate\DTO\Company as CompanyDto;
use App\Domain\Feature\Exception\CompanyAlreadyExistsException;
use App\Domain\Feature\Exception\CompanyNotFoundException;
use App\Domain\Repository\CompanyRepositoryInterface;

final readonly class CompanyUpdateService implements CompanyUpdateInterface
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function updateCompany(CompanyDto $companyDto): void
    {
        $this->assertCompanyCanBeUpdated($companyDto);

        /**
         * @var Company $company
         */
        $company = $this->companyRepository->findById($companyDto->id);

        $company->updateFromDto($companyDto);

        $this->companyRepository->add($company);
        $this->companyRepository->confirm();
    }

    private function assertCompanyCanBeUpdated(CompanyDto $companyDto): void
    {
        $company = $this->companyRepository->findById($companyDto->id);

        if (null === $company) {
            throw CompanyNotFoundException::create();
        }

        $companyByTaxId = $this->companyRepository->findByTaxId(Country::PL, $companyDto->taxId);

        if (null !== $companyByTaxId && $companyByTaxId->getId()->toBase32() !== $company->getId()->toBase32()) {
            throw CompanyAlreadyExistsException::byTaxId();
        }
    }
}
