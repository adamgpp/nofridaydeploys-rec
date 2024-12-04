<?php

declare(strict_types=1);

namespace App\Domain\Feature\CompanyCreation\Validation;

use App\Domain\Country;
use App\Domain\Feature\Exception\CompanyAlreadyExistsException;
use App\Domain\Repository\CompanyRepositoryInterface;
use App\Domain\ValueObject\BaseString;
use Symfony\Component\Uid\Ulid;

final class CompanyCreationValidator implements CompanyCreationValidationInterface
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
    ) {
    }

    public function assertCompanyCanBeCreated(Ulid $id, BaseString $taxId): void
    {
        if ($this->companyRepository->existsById($id)) {
            throw CompanyAlreadyExistsException::byId();
        }

        if ($this->companyRepository->existsByTaxId(Country::PL, $taxId)) {
            throw CompanyAlreadyExistsException::byTaxId();
        }
    }
}
